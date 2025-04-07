<?php

namespace LaraZeus\Replies\Livewire;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Foundation\Application as ApplicationAlias;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Livewire\Component;

/**
 * @property mixed $form
 */
class Comments extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Model $item;

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->statePath('data')
            ->schema([
                MarkdownEditor::make('comment')
                    ->toolbarButtons(config('zeus-replies.comments-editor-toolbar'))
                    ->columnSpanFull()
                    ->label(__('zeus-replies::replies.add_reply'))
                    ->required(),
            ]);
    }

    public function doSubmit(): void
    {
        // @phpstan-ignore-next-line
        $this->dispatch('add-comment', itemId: $this->item->id);

        // @phpstan-ignore-next-line
        $this->item->comment($this->form->getState()['comment']);
        $this->data = [];
    }

    public function render(): View | Application | Factory | ApplicationAlias
    {
        // @phpstan-ignore-next-line
        return view('zeus-replies::replies')
            ->with(
                'comments',
                // @phpstan-ignore-next-line
                $this->item->comments()->get()
            );
    }
}
