<?php

$feedbackString = $_POST['feedbackString'];
$json     	    = $_POST['jsonFile'];

//$feedbackString = '[{"feedbackIndex":"4","comment":"Actualizando","status":"1","leftPosition":"75.72916666666667","topPosition":"41.890062279989166"}]';
//$json     		= 'demo.json';

$fileDir = '../feedbacks/';
$feedbackList = array();
$feedbackFile = $fileDir.$json;
$data = file_get_contents($feedbackFile);
$data = json_decode($data);
$item = 0;
$update = false;

$newFeedback = json_decode($feedbackString);

if($data){
	foreach ($data as $feedback) {
		++$item;
		if($item == $newFeedback[0]->feedbackIndex){
			$update = true;
			$feedbackList[] =  array(
				'feedbackIndex' => $item,
				'comment' 	    => $newFeedback[0]->comment,
				'leftPosition'  => $newFeedback[0]->leftPosition,
				'topPosition'   => $newFeedback[0]->topPosition,
				'status'        => isset($newFeedback[0]->status) ? $newFeedback[0]->status : 0,
			);
		} else {
			$feedbackList[] =  array(
				'feedbackIndex' => $item,
				'comment' 	    => $feedback->comment,
				'leftPosition'  => $feedback->leftPosition,
				'topPosition'   => $feedback->topPosition,
				'status'        => isset($feedback->status) ? $feedback->status : 0,
			);
		}
	};
};

if(!$update){
	foreach ($newFeedback as $content) {
		$feedbackList[] =  array(
			'feedbackIndex' => ++$item,
			'comment' 	    => $content->comment,
			'leftPosition'  => $content->leftPosition,
			'topPosition'   => $content->topPosition,
			'status'        => 1,
		);
	}
}

$feedbackString = json_encode($feedbackList);
file_put_contents($feedbackFile, $feedbackString);

?>
