<div class="gallery-approval ">
    <div class="row images-container">
        @foreach ($images as $image)
            <div class="col-2 p-1 py-0 {{ ($image->approval == 1) ? "imgCheck":"" }}">
                <div class="img-item mb-2">
                    <a href="{{ route("approvals.detail", $image->id)}}">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $image->id }}" id="checkImage" data-toggle="checkImage" {{ ($image->approval == 1) ? "checked":"" }}>
                        </div>
                        <div id="overlay"></div>
                        @php
                            $image_name = substr(strrchr($image->image_path, "/"), 1);
                            $title = $image->description . (empty($image->preset) ? '' : ' - '.$image->preset);
                        @endphp
                        @if(file_exists('ib/thumbnails/thumb_'.$image_name))
                            <img id="img_{{ $image->id }}" class="lazy img-fluid img {{ ($image->approval == 1) ? "active":"" }}" src="{{ url('ib/thumbnails/thumb_'.$image_name) }}" />
                        @else
                            <img id="img_{{ $image->id }}" class="lazy img-fluid img {{ ($image->approval == 1) ? "active":"" }}" src="{{ $image->image_path }}" />
                        @endif
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>