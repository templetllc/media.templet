<div class="gallery-masonry ">
    <div class="masonry-row images-container">
        @php 
            $row = 0; 
            $column1 = $column2 = $column3 = $column4 = $column5 = $column6 = "";
            $media_item = "";
        @endphp

        @foreach ($images as $key => $image)
        @php
            $index = $loop->iteration - $row;
            $image_name = substr(strrchr($image->image_path, "/"), 1);
            $title = $image->description . (empty($image->preset) ? '' : ' - '.$image->preset);

            $media_item = '<div class="media-item loading" data-category="'.(empty($image->category) ? 'uncategorized': str_replace(" ","_",strtolower($image->category))).'" data-preset="'.(empty($image->preset) ? 'original_size': $image->preset).'" data-tags="'.$image->tags.'" data-date="'.$image->created_at->format('Y').'_'.$image->created_at->format('n').'">';
            $media_item .= '<a href="'.url('ib/'.$image->image_id).'" class="hasTooltip">';
            if(strlen($title) > 0){
                $media_item .= '   <span class="tiptext">'.$title.'</span>';
            }
            //$media_item .= '<img src="'.$image->image_path.'">';
            if(file_exists('ib/thumbnails/thumb_'.$image_name)){
                $media_item .= '<img class="lazy" data-src="'.url('ib/thumbnails/thumb_'.$image_name).'" />';
            } else {
                $media_item .= '<img class="lazy" data-src="'.$image->image_path.'" />';
            }
            $media_item .= '<div class="overlay">';
            $media_item .= '   <input type="text" id="'.$image->id.'" value="'.$image->image_path.'" style="z-index: -99; opacity: 0; position: absolute;">';
            $media_item .= '   <div class="icon-view">';
            $media_item .= '       <button class="border-0 bg-transparent">';
            $media_item .= '            <svg xmlns="http://www.w3.org/2000/svg" width="19.72" height="19.72" viewBox="0 0 19.72 19.72">';
            $media_item .= '            <g id="Icon_feather-search" data-name="Icon feather-search" transform="translate(-14.53 248.603)">';
            $media_item .= '            <path id="Union_16" data-name="Union 16" d="M1665.72-491.53a.747.747,0,0,1-.53-.22l-3.989-3.988a8.723,8.723,0,0,1-5.662,2.066,8.8,8.8,0,0,1-8.789-8.789,8.8,8.8,0,0,1,8.789-8.789,8.8,8.8,0,0,1,8.789,8.789,8.723,8.723,0,0,1-2.066,5.662l3.989,3.988a.748.748,0,0,1,.22.53.748.748,0,0,1-.22.53A.748.748,0,0,1,1665.72-491.53Zm-10.181-18.22a7.3,7.3,0,0,0-7.289,7.289,7.3,7.3,0,0,0,7.289,7.289,7.241,7.241,0,0,0,5.154-2.135,7.241,7.241,0,0,0,2.135-5.154A7.3,7.3,0,0,0,1655.539-509.75Z" transform="translate(-1632.22 262.647)" fill="#fff"/>';
            $media_item .= '            </g>';
            $media_item .= '            </svg>';
            $media_item .= '       </button>';
            $media_item .= '   </div>';

            $media_item .= '   <div class="icon-action">';
            $media_item .= '       <button role="button" data-id="'.$image->id.'" class="ico-copy btn-tooltip" title="Copy URL">';
            $media_item .= '          <span class="tiptextBtn">Copy URL</span>';
            // $media_item .= '          <svg id="ico-copy" xmlns="http://www.w3.org/2000/svg" width="21.917" height="21.917" viewBox="0 0 21.917 21.917">';
            // $media_item .= '             <path id="Trazado_25" data-name="Trazado 25" d="M0,0H21.917V21.917H0Z" fill="none"/>';
            // $media_item .= '             <path id="Unión_4" data-name="Unión 4" d="M-288.153-614.653a2.5,2.5,0,0,1-2.5-2.5v-.889h-1.021A2.329,2.329,0,0,1-294-620.368v-7.305A2.33,2.33,0,0,1-291.674-630h7.306a2.329,2.329,0,0,1,2.326,2.326v1.021h.889a2.5,2.5,0,0,1,2.5,2.5v7a2.5,2.5,0,0,1-2.5,2.5Zm-1.5-9.5v7a1.5,1.5,0,0,0,1.5,1.5h7a1.5,1.5,0,0,0,1.5-1.5v-7a1.5,1.5,0,0,0-1.5-1.5h-7A1.5,1.5,0,0,0-289.652-624.153ZM-293-627.674v7.305a1.328,1.328,0,0,0,1.327,1.327h1.021v-5.111a2.5,2.5,0,0,1,2.5-2.5h5.111v-1.021A1.328,1.328,0,0,0-284.368-629h-7.306A1.328,1.328,0,0,0-293-627.674Z" transform="translate(297.153 633.153)" fill="#fff"/>';
            // $media_item .= '          </svg>';
            $media_item .= '            <img src="/assets/images/ico-link.png" height="21">';
            $media_item .= '       </button>';

            $media_item .= '       <button data-url="'.route('download.image', $image->image_id).'" class="btn-download pe-1 btn-tooltip">';
            $media_item .= '          <span class="tiptextBtn">Download</span>';
            $media_item .= '          <svg id="ico-download" xmlns="http://www.w3.org/2000/svg" width="21.917" height="21.917" viewBox="0 0 21.917 21.917">';
            $media_item .= '             <path id="Trazado_27" data-name="Trazado 27" d="M0,0H21.917V21.917H0Z" fill="rgba(0,0,0,0)"/>';
            $media_item .= '             <path id="Unión_6" data-name="Unión 6" d="M-316.911-613.576l-2.74-2.74a.5.5,0,0,1,0-.707.5.5,0,0,1,.706,0l1.879,1.878v-6.875a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v6.89l1.894-1.894a.5.5,0,0,1,.707,0,.5.5,0,0,1,0,.707l-2.74,2.74a.5.5,0,0,1-.353.146A.5.5,0,0,1-316.911-613.576Zm6.246-4.007a.5.5,0,0,1,.5-.5,2.677,2.677,0,0,0,1.906-.79,2.678,2.678,0,0,0,.79-1.906,2.7,2.7,0,0,0-2.7-2.7h-.913a.5.5,0,0,1-.39-.187.5.5,0,0,1-.1-.422,3.715,3.715,0,0,0-.549-2.888,4.654,4.654,0,0,0-3.06-1.944,4.636,4.636,0,0,0-5.46,3.225.5.5,0,0,1-.508.39,3.684,3.684,0,0,0-3.757,2.7,3.48,3.48,0,0,0,2.058,4.011.5.5,0,0,1,.259.658.5.5,0,0,1-.659.259,4.478,4.478,0,0,1-2.629-5.17,4.669,4.669,0,0,1,4.364-3.458,5.7,5.7,0,0,1,6.51-3.6,5.652,5.652,0,0,1,3.714,2.375,4.73,4.73,0,0,1,.788,3.05h.329a3.7,3.7,0,0,1,3.7,3.7,3.671,3.671,0,0,1-1.083,2.614,3.671,3.671,0,0,1-2.614,1.083A.5.5,0,0,1-310.665-617.583Z" transform="translate(327.516 632.673)" fill="#fff"/>';
            $media_item .= '          </svg>';
            $media_item .= '       </button>';

            $media_item .= '       <button data-id="'.$image->id.'" id="duplicateImage" class="text-muted btn-tooltip">';
            $media_item .= '          <span class="tiptextBtn">Duplicate</span>';
            $media_item .= '          <svg id="ico-copy" xmlns="http://www.w3.org/2000/svg" width="21.917" height="21.917" viewBox="0 0 21.917 21.917">';
            $media_item .= '             <path id="Trazado_25" data-name="Trazado 25" d="M0,0H21.917V21.917H0Z" fill="none"/>';
            $media_item .= '             <path id="Unión_4" data-name="Unión 4" d="M-288.153-614.653a2.5,2.5,0,0,1-2.5-2.5v-.889h-1.021A2.329,2.329,0,0,1-294-620.368v-7.305A2.33,2.33,0,0,1-291.674-630h7.306a2.329,2.329,0,0,1,2.326,2.326v1.021h.889a2.5,2.5,0,0,1,2.5,2.5v7a2.5,2.5,0,0,1-2.5,2.5Zm-1.5-9.5v7a1.5,1.5,0,0,0,1.5,1.5h7a1.5,1.5,0,0,0,1.5-1.5v-7a1.5,1.5,0,0,0-1.5-1.5h-7A1.5,1.5,0,0,0-289.652-624.153ZM-293-627.674v7.305a1.328,1.328,0,0,0,1.327,1.327h1.021v-5.111a2.5,2.5,0,0,1,2.5-2.5h5.111v-1.021A1.328,1.328,0,0,0-284.368-629h-7.306A1.328,1.328,0,0,0-293-627.674Z" transform="translate(297.153 633.153)" fill="#fff"/>';
            $media_item .= '          </svg>';
            $media_item .= '       </button>';

            if(isset($image->user->id)){
                if(Auth::user()->id == $image->user->id){
                    $media_item .= '       <button data-id="'.$image->id.'" id="deleteImage" class="text-muted btn-tooltip">';
                    $media_item .= '          <span class="tiptextBtn">Delete</span>';
                    //$media_item .= '            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';
                    $media_item .= '            <svg id="ico-delete" xmlns="http://www.w3.org/2000/svg" width="21.917" height="21.917" viewBox="0 0 21.917 21.917">';
                    $media_item .= '                <path id="Trazado_30" data-name="Trazado 30" d="M0,0H21.917V21.917H0Z" fill="none"/>';
                    $media_item .= '                <path id="Unión_7" data-name="Unión 7" d="M-353.1-612.562a2.328,2.328,0,0,1-2.327-2.3l-.906-10.873h-.171a.5.5,0,0,1-.5-.5.5.5,0,0,1,.5-.5h3.817v-1.848A1.414,1.414,0,0,1-351.27-630h3.652a1.415,1.415,0,0,1,1.414,1.413v1.848h3.7a.5.5,0,0,1,.5.5.5.5,0,0,1-.5.5h-.059l-.905,10.873a2.33,2.33,0,0,1-2.327,2.3Zm-1.328-2.368c0,.014,0,.028,0,.041a1.328,1.328,0,0,0,1.327,1.327h7.305a1.328,1.328,0,0,0,1.327-1.327.325.325,0,0,1,0-.041l.9-10.809h-11.763Zm7.221-11.809v-1.848a.414.414,0,0,0-.414-.413h-3.652a.414.414,0,0,0-.413.413v1.848Zm-.8,10.5v-6a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v6a.5.5,0,0,1-.5.5A.5.5,0,0,1-348-616.24Zm-4,0v-6a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v6a.5.5,0,0,1-.5.5A.5.5,0,0,1-352-616.24Z" transform="translate(360.402 632.24)" fill="#fff"/>';
                    $media_item .= '            </svg>';
                    $media_item .= '       </button>';

                }
            }

            $media_item .= '   </div>';
            $media_item .= '</div>';
            $media_item .= '</a></div>';

            switch($index){
                case 1:
                    $column1 .= $media_item;

                    break;

                case 2:
                    $column2 .= $media_item;
                    break;

                case 3:
                    $column3 .= $media_item;
                    break;

                case 4:
                    $column4 .= $media_item;
                    break;

                case 5:
                    $column5 .= $media_item;
                    break;

                case 6:
                    $column6 .= $media_item;
                    $row = $row + 6;
                    break;
            }
            @endphp
        @endforeach

        <div class="column">
            {!! $column1 !!}
        </div>
        <div class="column">
            {!! $column2 !!}
        </div>
        <div class="column">
            {!! $column3 !!}
        </div>
        <div class="column">
            {!! $column4 !!}
        </div>
        <div class="column">
            {!! $column5 !!}
        </div>
        <div class="column">
            {!! $column6 !!}
        </div>
    </div>
</div>
