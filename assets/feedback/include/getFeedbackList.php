<?php

$feedback   = $_GET['feedback'];
$width      = $_GET['width'];
$height     = $_GET['height'];


// $feedback = "feedback_test.json";
// $width    = 1920;
// $height   = 4633;

$fileDir = '../feedbacks/';
if(!is_dir($fileDir)){
    mkdir($fileDir, 0777);
}

$feedbackList = $fileDir.$feedback;
$data = file_get_contents($feedbackList);
$data = json_decode($data);
$html = $marks = "";
$pending = 0;

$feedback_element = array();

if($data){
    $feedbacks = count($data);

    $feedback_list = "";
    foreach ($data as $index => $feedback) {
        $fb_item = $index + 1;

        $left   = ($feedback->leftPosition * $width / 100);
        $top    = ($feedback->topPosition * $height / 100);
        $status = (isset($feedback->status) ? $feedback->status : '1');
        //Marks
        $resolved = ($status == 2) ? 'feedback-pin_resolved':'';
        $visibled = ($status == 2) ? 'visible="hidden"':'visible="visible"';
        $marks .= '<div id="feedback-pin'.$fb_item.'" '.$visibled.' class="feedback-pin '.$resolved.'" feedbackIndex="'.$fb_item.'" leftPosition="'.$feedback->leftPosition.'" topPosition="'.$feedback->topPosition.'" style="left: '.$left.'px; top: '.$top.'px">';
        $marks .= '<span>'.$fb_item.'</span></div>';

        if($status == 2){ //Resolved
            $html .= '<div class="sidebar-resolved sidebar-feedback" data-index="'.$fb_item.'" visible="hidden" data-status="'.$status.'">';
            $html .= '    <div class="d-flex justify-content-between mb-3">';
            $html .= '        <div>';
            $html .= '            <span class="badge badge-light"><span class="item-number">'.$fb_item.'</span></span>';
            $html .= '        </div>';
            $html .= '        <div class="dropdown">';
            $html .= '            <a id="dropdown-'.$fb_item.'" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $html .= '                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="4" viewBox="0 0 16 4">';
            $html .= '                    <path id="path-submenu" d="M-134-3537a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-134-3537Zm-6,0a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-140-3537Zm-6,0a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-146-3537Z" transform="translate(146 3539)" fill="#9c9d9e"/>';
            $html .= '                </svg>';
            $html .= '            </a>';
            $html .= '            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
            $html .= '                <a class="dropdown-item btn-unresolved" >';
            $html .= '                    <svg xmlns="http://www.w3.org/2000/svg" width="14.186" height="14.187" viewBox="0 0 14.186 14.187">';
            $html .= '                        <path id="path-unresolved" d="M-1809.88-3906.854a.474.474,0,0,1-.282-.433v-2.365h-1.23a1.609,1.609,0,0,1-1.607-1.607v-8.134a1.61,1.61,0,0,1,1.607-1.608h10.971a1.61,1.61,0,0,1,1.608,1.608v8.134a1.61,1.61,0,0,1-1.608,1.607h-5.989l-2.96,2.713a.471.471,0,0,1-.319.125A.47.47,0,0,1-1809.88-3906.854Zm-2.174-12.539v8.134a.662.662,0,0,0,.661.661h1.7a.473.473,0,0,1,.473.473v1.762l2.3-2.111a.472.472,0,0,1,.32-.125h6.173a.662.662,0,0,0,.662-.661v-8.134a.662.662,0,0,0-.662-.662h-10.971A.662.662,0,0,0-1812.054-3919.392Zm2.837,4.539a.473.473,0,0,1-.473-.472.473.473,0,0,1,.473-.473h6.621a.473.473,0,0,1,.472.473.472.472,0,0,1-.472.472Z" transform="translate(1813 3921)" fill="#656d77"/>';
            $html .= '                    </svg>                                                                          ';
            $html .= '                    <span>Move to unresolved</span> ';
            $html .= '                </a>';
            $html .= '                <a class="dropdown-item btn-delete_feedback" >';
            $html .= '                    <svg xmlns="http://www.w3.org/2000/svg" width="14.26" height="16.567" viewBox="0 0 14.26 16.567">';
            $html .= '                        <path id="path-delete" d="M3.723-3524.432a2.232,2.232,0,0,1-2.229-2.208l-.856-10.272H.5a.5.5,0,0,1-.5-.5.5.5,0,0,1,.5-.5H4.089v-1.723A1.366,1.366,0,0,1,5.453-3541h3.46a1.367,1.367,0,0,1,1.365,1.365v1.723h3.481a.5.5,0,0,1,.5.5.5.5,0,0,1-.5.5h-.031l-.857,10.272a2.232,2.232,0,0,1-2.229,2.208ZM2.492-3526.7c0,.014,0,.028,0,.042a1.231,1.231,0,0,0,1.229,1.229h6.919a1.231,1.231,0,0,0,1.229-1.229c0-.014,0-.028,0-.042l.851-10.208H1.641Zm6.785-11.208v-1.723a.365.365,0,0,0-.365-.365H5.453a.365.365,0,0,0-.365.365v1.723Zm-.753,9.97v-5.683a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v5.683a.5.5,0,0,1-.5.5A.5.5,0,0,1,8.524-3527.942Zm-3.788,0v-5.683a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v5.683a.5.5,0,0,1-.5.5A.5.5,0,0,1,4.736-3527.942Z" transform="translate(0 3541)" fill="#d63939"/>';
            $html .= '                    </svg>';
            $html .= '                    <span>Delete</span>';
            $html .= '                </a>';
            $html .= '            </div>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '    <div>';
            $html .= '        <h3 class="text-comment">'.$feedback->comment.'</h3>';
            $html .= '    </div>';
            $html .= '</div>';
        } else {
            $pending++;
            $html .= '<div class="sidebar-comment sidebar-feedback" data-index="'.$fb_item.'" visible="visible" data-status="'.$status.'">';
            $html .= '    <div class="d-flex justify-content-between mb-3">';
            $html .= '        <div>';
            $html .= '            <span class="badge badge-light"><span class="item-number">'.$fb_item.'</span></span>';
            $html .= '        </div>';
            $html .= '        <div class="dropdown">';
            $html .= '            <a class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $html .= '                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="4" viewBox="0 0 16 4">';
            $html .= '                    <path id="path-submenu" d="M-134-3537a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-134-3537Zm-6,0a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-140-3537Zm-6,0a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-146-3537Z" transform="translate(146 3539)" fill="#9c9d9e"/>';
            $html .= '                </svg>';
            $html .= '            </a>';
            $html .= '            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
            $html .= '                <a class="dropdown-item btn-resolve" >';
            $html .= '                    <svg xmlns="http://www.w3.org/2000/svg" width="14.28" height="14.281" viewBox="0 0 14.28 14.281">';
            $html .= '                        <path id="path-resolve" d="M-1814-3814.86a7.139,7.139,0,0,1,7.14-7.14,7.139,7.139,0,0,1,7.14,7.14,7.139,7.139,0,0,1-7.14,7.141A7.14,7.14,0,0,1-1814-3814.86Zm2.77-4.37a6.139,6.139,0,0,0-1.809,4.37,6.135,6.135,0,0,0,1.809,4.37,6.139,6.139,0,0,0,4.37,1.81,6.137,6.137,0,0,0,4.37-1.81,6.135,6.135,0,0,0,1.809-4.37,6.139,6.139,0,0,0-1.809-4.37,6.141,6.141,0,0,0-4.37-1.809A6.143,6.143,0,0,0-1811.23-3819.23Zm2.64,7.007-1.922-1.922a.137.137,0,0,1,0-.2l.611-.611a.135.135,0,0,1,.1-.041.136.136,0,0,1,.1.041l1.524,1.524,4.188-4.219a.123.123,0,0,1,.1-.041.128.128,0,0,1,.1.041l.6.622a.146.146,0,0,1,0,.2l-4.59,4.614h0a.638.638,0,0,1-.4.189A.609.609,0,0,1-1808.59-3812.223Z" transform="translate(1814 3822)" fill="#656d77"/>';
            $html .= '                    </svg>';
            $html .= '                    <span>Resolve</span> ';
            $html .= '                </a>';
            $html .= '                <a class="dropdown-item btn-edit_feedback" >';
            $html .= '                    <svg xmlns="http://www.w3.org/2000/svg" width="14.282" height="14.261" viewBox="0 0 14.282 14.261">';
            $html .= '                        <path id="path-edit" d="M-1812.788-3839.739a1.213,1.213,0,0,1-1.212-1.212v-9.41a1.213,1.213,0,0,1,1.212-1.212h5.019a.447.447,0,0,1,.447.447.448.448,0,0,1-.447.448h-5.019a.32.32,0,0,0-.32.32v9.407a.321.321,0,0,0,.32.32h9.4a.321.321,0,0,0,.32-.32v-4.815a.448.448,0,0,1,.448-.447.447.447,0,0,1,.447.447v4.815a1.213,1.213,0,0,1-1.212,1.212Zm1.845-3.06a.751.751,0,0,1-.192-.686l.1-.46c.173-.819.351-1.663.532-2.493a1.036,1.036,0,0,1,.266-.486c.4-.407.812-.817,1.208-1.214l1.667-1.667c1.279-1.279,2.6-2.6,3.894-3.9a.91.91,0,0,1,.663-.3.891.891,0,0,1,.113.007l.055.007a2.347,2.347,0,0,1,.593.132,3.838,3.838,0,0,1,2.135,2.093,2.584,2.584,0,0,1,.137.475l0,.017.019.087c.007.029.013.057.019.085a.471.471,0,0,1,.012.147,1.046,1.046,0,0,1-.309.669c-2.244,2.241-4.525,4.521-6.778,6.777a1.026,1.026,0,0,1-.526.284c-.559.116-1.129.236-1.679.352l-.739.157-.506.106a1.008,1.008,0,0,1-.165.016A.739.739,0,0,1-1810.943-3842.8Zm8.105-10.283c-1.3,1.306-2.622,2.627-3.9,3.906l-1.366,1.366c-.452.449-.943.942-1.5,1.508a.155.155,0,0,0-.029.05c-.152.7-.3,1.421-.451,2.114l-.129.606,2.7-.569a.135.135,0,0,0,.076-.041l1.7-1.7c1.666-1.666,3.387-3.388,5.083-5.08a.135.135,0,0,0,.042-.06c0-.016-.007-.031-.01-.046s-.011-.047-.016-.072a1.777,1.777,0,0,0-.085-.313,2.976,2.976,0,0,0-1.654-1.623,1.452,1.452,0,0,0-.372-.074l-.043,0h0A.234.234,0,0,0-1802.838-3853.083Z" transform="translate(1814 3854)" fill="#656d77"/>';
            $html .= '                    </svg>';
            $html .= '                    <span>Edit</span>';
            $html .= '                </a>';
            $html .= '                <a class="dropdown-item btn-delete_feedback" >';
            $html .= '                    <svg xmlns="http://www.w3.org/2000/svg" width="14.26" height="16.567" viewBox="0 0 14.26 16.567">';
            $html .= '                        <path id="path-delete" d="M3.723-3524.432a2.232,2.232,0,0,1-2.229-2.208l-.856-10.272H.5a.5.5,0,0,1-.5-.5.5.5,0,0,1,.5-.5H4.089v-1.723A1.366,1.366,0,0,1,5.453-3541h3.46a1.367,1.367,0,0,1,1.365,1.365v1.723h3.481a.5.5,0,0,1,.5.5.5.5,0,0,1-.5.5h-.031l-.857,10.272a2.232,2.232,0,0,1-2.229,2.208ZM2.492-3526.7c0,.014,0,.028,0,.042a1.231,1.231,0,0,0,1.229,1.229h6.919a1.231,1.231,0,0,0,1.229-1.229c0-.014,0-.028,0-.042l.851-10.208H1.641Zm6.785-11.208v-1.723a.365.365,0,0,0-.365-.365H5.453a.365.365,0,0,0-.365.365v1.723Zm-.753,9.97v-5.683a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v5.683a.5.5,0,0,1-.5.5A.5.5,0,0,1,8.524-3527.942Zm-3.788,0v-5.683a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v5.683a.5.5,0,0,1-.5.5A.5.5,0,0,1,4.736-3527.942Z" transform="translate(0 3541)" fill="#d63939"/>';
            $html .= '                    </svg>';
            $html .= '                    <span>Delete</span>';
            $html .= '                </a>';
            $html .= '            </div>';
            $html .= '        </div>';
            $html .= '    </div>';
            $html .= '    <div>';
            $html .= '        <h3 class="text-comment">'.$feedback->comment.'</h3>';
            $html .= '    </div>';
            $html .= '</div>';

        }
    }

    $feedback_element[] = array(
        'marks'     => $marks,
        'comments'  => $html,
        'count'     => $fb_item,
        'pending'   => $pending
    );
} else {
    //Create json file
    $fileName = $fileDir.$feedback;
    file_put_contents($fileName, '');
}
if(count($feedback_element) > 0){
    echo json_encode($feedback_element);
} 


?>

