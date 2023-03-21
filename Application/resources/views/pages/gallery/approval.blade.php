<div class="gallery-approval ">
    <div class="row images-container">
        @foreach ($images as $image)
            <div class="{{ $type === 'icons' ? 'col-1' : 'col-2' }} p-1 py-0 {{ ($image->approval == 1) ? 'imgCheck' : '' }}">
                <div class="img-item mb-2">
                    <a href="{{ route('approvals.detail', array_merge(array($type, $image->id, $status), request()->query())) }}">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="{{ $image->id }}"
                                id="checkImage"
                                data-toggle="checkImage"
                            >
                        </div>
                        @if($image->approval === 1)
                            <span class="tag">Approved</span>
                        @endif
                        @if($image->approval === 0)
                            <span class="tag-unapproved">Unapproved</span>
                        @endif
                        <div id="overlay"></div>
                        @php
                            $image_name = substr(strrchr($image->image_path, "/"), 1);
                            $title = $image->description . (empty($image->preset) ? '' : ' - '.$image->preset);
                        @endphp
                        @if(file_exists('ib/thumbnails/thumb_'.$image_name))
                            <img id="img_{{ $image->id }}" class="lazy img-fluid img" src="{{ url('ib/thumbnails/thumb_'.$image_name) }}" />
                        @else
                            <img id="img_{{ $image->id }}" class="lazy img-fluid img" src="{{ $image->image_path }}" />
                        @endif
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
