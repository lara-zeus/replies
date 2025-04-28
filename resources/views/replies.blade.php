<div
    @if(config('zeus-replies.chat_polling.enabled')) wire:poll.{{ config('zeus-replies.chat_polling.time') }} @endif
>
    <div x-data class="my-10 overflow-y-auto max-h-[calc(100vh/2)] px-1">
        @foreach($comments as $comment)
            @php $isOwner = $comment->user_id === auth()->user()->id; @endphp
            <div class="flex items-center @if($isOwner) justify-end @endif">
                <div
                    @if($loop->last) x-init="$el.scrollIntoView({block: 'nearest', behavior: 'smooth'})" @endif
                    class="flex flex-col text-xs max-w-xs m-2 mx-3 @if($isOwner) order-1 @else order-2 @endif items-end">
                    <div class="prose dark:prose-invert px-3 py-2 @if($isOwner) !rounded-br-none bg-primary-50 dark:bg-primary-600 text-gray-600 dark:text-gray-100 @else !rounded-bl-none bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-100 @endif rounded-2xl">
                        {!!
                            (new \Illuminate\Support\HtmlString(
                                str(strip_tags($comment->comment))
                                    ->replace(['prompt(','eval(','&lt;script','<script'],'')
                                    ->markdown()
                            ))->toHtml();
                        !!}
                    </div>
                    <span
                        x-tooltip="{
                            content: @js($comment->created_at->format($this->form->getDefaultDateDisplayFormat())),
                            theme: $store.theme,
                        }"
                        class="transition-all ease-in-out duration-150 @if($isOwner) text-right @else text-left @endif w-full flex flex-col cursor-pointer text-xs text-gray-500 dark:text-gray-200 my-1"
                    >
                        {{ $comment->created_at->diffForHumans() }}
                    </span>
                </div>

                <div class="@if($isOwner) order-2 @else order-1 @endif">
                    <x-zeus::user-card>
                        <x-slot name="trigger">
                            <img
                                src="{{ \Filament\Facades\Filament::getUserAvatarUrl($comment->commentator) }}"
                                alt="profile" class="inline-block h-9 w-9 rounded-full"
                            >
                        </x-slot>
                    </x-zeus::user-card>
                </div>
            </div>
        @endforeach
    </div>

    {{-- todo --}}
    {{--@canany(['reply','all'], $item)--}}
        <form wire:submit="doSubmit">
            <hr class="my-2"/>

            {{ $this->form }}

            <div class="text-center mt-4">
                <x-filament::button type="submit">
                    {{ __('zeus-replies::replies.submit_btn') }}
                </x-filament::button>
            </div>
        </form>
    {{--@endcan--}}
</div>
