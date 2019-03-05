<?php

namespace Ecd\Feedbacktool\Models;

use Auth;
use Ecd\Feedbacktool\Models\Gitnote;
Use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'issues';

    public static function checkForUpdates() {

        include __DIR__."/../config.php";

        $headers = ['PRIVATE-TOKEN: '.$accessToken];
        $ch = curl_init($apiUrl.'/projects/'.$projectId.'/issues');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $issues = json_decode($response, 1);

        if ($issues == null) {
            echo "Error no response from GitLab, is your config.php file setup? ";
            die();
        } else {
            foreach ($issues as $issue) {
                if ($issue == "404 Not Found") {
                    echo "404, GitLab API Not Found. Check the apiUrl in your config.php file. ";
                    die();
                }

                if ($issue == "404 Project Not Found") {
                    echo "404, Project Not Found. Check the projectId in your config.php file. ";
                    die();
                }

                if ($issue == "401 Unauthorized") {
                    echo "401, Unauthorised. Check your access token. ";
                    die();
                }

                $assignees = '';

                foreach ($issue['assignees'] as $assignee) {
                    $assignees .=$assignee['name'];
                }

                $labels = implode(",", $issue['labels']);
                $iid = $issue['iid'];

                Issue::where('issue_id', $iid)->update([
                    'title' => $issue['title'],
                    'description' => $issue['description'],
                    'labels' => $labels,
                    'user_notes_count' => $issue['user_notes_count'],
                    'assignee' => $assignees,
                    'state' => $issue['state']
                ]);

                $notesCh = curl_init($apiUrl.'/projects/'.$projectId.'/issues/'.$iid.'/notes');
                curl_setopt($notesCh, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($notesCh, CURLOPT_RETURNTRANSFER, true);
                $notesResponse = curl_exec($notesCh);
                curl_close($notesCh);
                $issueNotes = json_decode($notesResponse, 1);


                foreach ($issueNotes as $note) {
                    if ($note == "404 Not Found") {
                        dump("No notes found for issue ".$iid);
                    } else {
                        $comment_id = $note['id'];
                        $comment = $note['body'];
                        $developer_name = $note['author'];

                        $gitnote = Gitnote::updateOrCreate(
                            ['comment_id' => $comment_id],

                            [
                                'comment_id' => $comment_id,
                                'issue_id' => $iid,
                                'comment' => $comment,
                                'developer_name' => $developer_name['name']
                            ]
                        );
                    }
                }
            }
        }
    }



    public static function createIssue($gitlabResponse) {

        $labels = implode(",", $gitlabResponse['labels']);

        $issue = new Issue;

        $issue->issue_id = $gitlabResponse['iid'];
        $issue->user_id = Auth::user()->id;
        $issue->title = $gitlabResponse['title'];
        $issue->description = $gitlabResponse['description'];
        $issue->labels = $labels;
        $issue->user_notes_count = $gitlabResponse['user_notes_count'];
        $issue->state = $gitlabResponse['state'];

        $issue->save();
    }



    public static function sendToGitlab($request) {

        include __DIR__."/../config.php";

        $specifiedUser = Auth::user()->$userIdentifier;

        $post = [
            'title' => $request->title,
            'description' => $request->description.' (Submitted by '.$specifiedUser.')',
            'labels' => $request->label
        ];

        $headers = ['PRIVATE-TOKEN: '.$accessToken];

        $ch = curl_init('https://dev.ecdltd.com/api/v4/projects/'.$projectId.'/issues');
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $gitlabResponse = json_decode($response, true);

        Issue::createIssue($gitlabResponse);
    }

    public static function getIssueNotes($id) {

    }

    public function notes()
    {
        return $this->hasMany('Ecd\Feedbacktool\Models\Gitnote');
    }
}
?>