<tr>
    <td {{ $category->threadsEnabled ? '' : 'colspan=5' }}>
        <p class="{{ isset($titleClass) ? $titleClass : '' }}"><a href="{{ Forum::route('category.show', $category) }}">{{ $category->title }}</a></p>
        <span class="text-muted">{{ $category->description }}</span>
    </td>
    @if ($category->threadsEnabled)
        <td>{{ $category->thread_count }}</td>
        <td>{{ $category->post_count }}</td>
        <td>
            @if ($category->newestThread)
                <a href="{{ Forum::route('thread.show', $category->threads->first()) }}">
                    {{ $category->threads->first()->title }}
                    ({{ $category->threads->first()->authorName }})
                </a>
            @endif
        </td>
        <td>
            @if ($category->latestActiveThread)
                <a href="{{ Forum::route('thread.show', $category->threads->first()->lastPost) }}">
                    {{ $category->threads->first()->title }}
                    ({{ $category->threads->first()->lastPost->authorName }})
                </a>
            @endif
        </td>
    @endif
</tr>
