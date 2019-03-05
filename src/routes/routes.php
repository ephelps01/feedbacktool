<?php

	Route::group(['middleware' => ['web', 'auth']], function(){
		Route::resource('/issue', 'ecd\feedbacktool\controllers\IssuesController');

		Route::get('/issue/{id}/notes', 'ecd\feedbacktool\controllers\IssuesController@getNotes');
	});

?>