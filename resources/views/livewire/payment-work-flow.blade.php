<div class="container">
    <div class="header" style="border-bottom: 1px solid #000;">
        <h1 style="font-size: 16px; font-weight: bold; width: 100%;">Title</h1>
        <p style="font-size: 13px; color: #999;">Sub Title</p>
    </div>

    <div class="body">
        @foreach($subContainers as $index => $subContainer)
            <div class="sub-container" wire:init="$el.style.display = 'none'" wire:transition.slide-left="$currentIndex == {{ $index }}" style="display: flex;">
                <h2>{{ $subContainer['title'] }}</h2>
                <p>{{ $subContainer['content'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="footer" style="margin-top: 10px; border-top: 1px solid #000;">
        <button class="btn btn-secondary" wire:click="mainMenu">Main Menu</button>
        <button class="btn btn-secondary" wire:click="back" wire:show="$currentIndex > 0">Back</button>
        <button class="btn btn-secondary" wire:click="next" wire:show="$currentIndex < {{ count($subContainers) - 1 }}">Next</button>
    </div>
</div>