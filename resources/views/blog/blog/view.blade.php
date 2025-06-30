@extends('layouts/contentLayoutMaster')

@section('title', 'Blog View')

@section('content')

<div class="card">
  <div class="card-header">

  </div>

  <div class="card-body">
  <div class="container">
    <div class="d-flex justify-content-center mb-1">
        @foreach($data->blogCategories as $category)
            <div class="border rounded-pill text-primary text-16 py-1 px-2 mx-1">
                {{ $category->name }}
            </div>
        @endforeach
    </div>

    <h1 style="font-size: 40px; font-weight: 700;" class="text-center mx-3 text-custom-blue">
        {{ $data->title }}
    </h1>

    <ul class="d-flex flex-column gap-2 flex-md-row gap-md-3 list-disc justify-content-center mx-auto mx-md-0 pt-3">
        <li class="text-primary">
            <span class="text-custom-blue font-weight-bold">Post By: </span>
            <span class="text-muted">{{ $data->authors->name }}</span>
        </li>

        <li class="text-primary">
            <span class="text-custom-blue font-weight-bold">Published: </span>
            <span class="text-muted">{{ $data->published_at }}</span>
        </li>
    </ul>

    <div class="d-flex justify-content-center pt-3">
        <img src="{{ $data->featured_image_url }}" alt="blog" class="img-fluid" />
    </div>

    <div class="d-md-none">
        <div  class="pt-4" ref="shareRef">
            <div class="text-center">
                <div class="text-custom-blue text-20">Share</div>
                <div class="d-flex justify-content-center mt-2">
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=" target="_blank">
                        <img src="{{ asset('images/social/linkedin.svg') }}" alt="linkedin" />
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=" target="_blank" class="mx-2">
                        <img src="{{ asset('images/social/facebook.svg') }}" alt="facebook" />
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=" target="_blank">
                        <img src="{{ asset('images/social/twitter.svg') }}" alt="twitter" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex px-2 justify-content-between pt-2">
        <div style="width: 70%;">
            <div ref="blogRef">
                @foreach($data->contents as $index => $content)
                    <div id="{{ $content->id }}">
                        @if($content->title)
                            <h2 style="font-size: 36px; font-weight: 700;" class="text-custom-blue pt-4">
                                {{ $content->title }}
                            </h2>
                        @endif
                        <div class="text-description text-18 pt-2 custom-css-blog">
                            {!! $content->description !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div style="width: 25%;">
            <div id="share-container" ref="shareRef">
                <div class="text-center">
                    <div class="text-custom-blue text-20">Share</div>
                    <div class="d-flex justify-content-center mt-2">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=" target="_blank">
                            <img src="{{ asset('images/social/linkedin.svg') }}" alt="linkedin" />
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=" target="_blank" class="mx-2">
                            <img src="{{ asset('images/social/facebook.svg') }}" alt="facebook" />
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=" target="_blank">
                            <img src="{{ asset('images/social/twitter.svg') }}" alt="twitter" />
                        </a>
                    </div>
                </div>
                <div>
                    @foreach($data->contents as $content)
                        <div>
                            <a href="#{{ $content->id }}">
                                <div class="text-description text-16 py-0 hover-text-primary">
                                    {{ $content->title }}
                                </div>
                            </a>
                            <hr class="border border-primary"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

  </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shareRef = document.getElementById('share-container');
        const sectionRef = document.getElementById('share-container'); 

        window.addEventListener('scroll', function() {
            const sectionBottom = sectionRef.getBoundingClientRect().bottom ;
            if (window.scrollY  > sectionBottom + 50) {
              console.log("test")
                shareRef.classList.add('fixed');
            } else {
                shareRef.classList.remove('fixed');
            }
        });
    });
</script>

@endsection