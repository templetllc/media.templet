<?php

$feedbackString = $_POST['feedbackString'];
$json     	  = $_POST['jsonFile'];

//$feedbackString = '[{"feedbackIndex":"4","comment":"Prueba","status":"0","leftPosition":"41.18055555555556","topPosition":"29.713211095439586"}]';
//$json     		= 'feedback_test.json';
//$json     		= 'demo.json';


//Feedback to delete
$newFeedback = json_decode($feedbackString);
foreach ($newFeedback as $content) {
	$feedbackDelete = $content->feedbackIndex;
}

$fileDir = '../feedbacks/';
$feedbackList = array();
$feedbackFile = $fileDir.$json;
$item = 0;
$data = file_get_contents($feedbackFile);
$data = json_decode($data);
if($data){
	foreach ($data as $key => $feedback) {

		if($feedback->feedbackIndex != $feedbackDelete){
			$item++;
			$feedbackList[] =  array(
				'feedbackIndex' => $item,
				'comment' 	    => $feedback->comment,
				'leftPosition'  => $feedback->leftPosition,
				'topPosition'   => $feedback->topPosition,
				'status'        => isset($feedback->status) ? $feedback->status : 0,
                'parentId'      => $feedback->parentId,
			);
		}
	};

	$feedbackString = json_encode($feedbackList);
	file_put_contents($feedbackFile, $feedbackString);
};

?>
