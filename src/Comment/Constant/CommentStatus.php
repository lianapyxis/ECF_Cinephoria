<?php

namespace App\Comment\Constant;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CommentStatus: string implements TranslatableInterface
{
    case DRAFT = 'DRAFT';
    case PUBLISHED = 'PUBLISHED';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        // Translate enum using custom labels
        return match ($this) {
            self::DRAFT  => $translator->trans('comment.draft.label', locale: $locale),
            self::PUBLISHED => $translator->trans('comment.published.label', locale: $locale),
        };
    }
}