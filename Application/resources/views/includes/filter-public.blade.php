<div class="row mb-4">
    <div class="col-12">
        <div class="card presets-box">
            <div class="card-body pt-1 pb-1 px-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-auto col-form-label">Presets</label>
                            <div class="col-12">
                                <select class="form-select" aria-label="Default select example" data-toggle="drop-preset">
                                    {{-- <option  value="">All presets</option> --}}
                                    @if(count($presets) > 0)
                                        @foreach($presets as $preset)
                                            @php 
                                                $preset_id = empty($preset->id) ? '0' : $preset->id 
                                            @endphp
                                            <option value="{{ $preset_id }}" {{ (app('request')->input('p')==$preset_id) ? 'selected="select"':''  }}>
                                                {{ empty($preset->preset) ? 'Original Size' : $preset->preset . ' ('.$preset->value.')' }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option  value="">Presets not found</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3 row">
                            <label for="typeImage" class="col-auto col-form-label">Image Group</label>
                            <div class="col-12">
                                <select class="form-select" aria-label="Default select example" id="typeGroup" data-toggle="drop-group" @if(Request::path()=='home' && empty(app('request')->input('c')) ) disabled @endif>
                                    {{-- <option  value="">All presets</option> --}}
                                    @php 
                                        $image_group = strlen(app('request')->input('g')) == 0 ? 1 : app('request')->input('g');
                                    @endphp
                                    <option value="0" {{ ($image_group == 0) ? 'selected="select"':''  }}>On Demand</option>
                                    <option value="1" {{ ($image_group == 1) ? 'selected="select"':''  }}>Gallery</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3 row">
                            <label for="tags" class="col-auto col-form-label">Tags</label>
                            <div class="col-12">
                                <select class="form-select js-select2" id="tags" aria-label="Default select example" data-toggle="drop-tags">
                                    @if(count($tags) > 0)
                                        <option value="">All tags</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag }}" {{ (app('request')->input('t')==$tag) ? 'selected="select"':''  }}>
                                                {{ strtolower($tag) }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Tags not found</option>
                                    @endif 
                                </select>
                                {{-- <input class="form-control " type="text" placeholder="Tags" aria-label="default input example" data-toggle="input-tags" value="{{ app('request')->input('t') }}"> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3 row">
                            <label for="typeImage" class="col-auto col-form-label">Image Type</label>
                            <div class="col-12">
                                <select class="form-select" aria-label="Default select example" id="typeImage" data-toggle="drop-type">
                                    {{-- <option  value="">All presets</option> --}}
                                    <option value="0" {{ (app('request')->input('s')==0) ? 'selected="select"':''  }}>Pictures</option>
                                    <option value="1" {{ (app('request')->input('s')==1) ? 'selected="select"':''  }}>Thumbnails</option>
                                    <option value="2" {{ (app('request')->input('s')==2) ? 'selected="select"':''  }}>Icons</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3 row">
                            <label for="date" class="col-auto col-form-label">Date</label>
                            <div class="col-12">
                                <select class="form-select" id="date" aria-label="Default select example" data-toggle="drop-date">
                                    <option value="">All months</option>
                                    @if(count($dates) > 0)
                                        @foreach($dates as $date)
                                            <option value="{{ $date->year.'_'.$date->month }}" {{ (app('request')->input('d')==$date->year.'_'.$date->month) ? 'selected="select"':''  }}>
                                                {{ $date->month_name.' '.$date->year }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>