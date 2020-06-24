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
                <?php
                $theThread = $category->threads()->search(request('q'))->latest()->first();

                if (!$theThread) {
                    $theThread = $category->newestThread;
                }
                ?>
                <a href="{{ Forum::route('thread.show', $theThread) }}">
                    {{ $theThread->title }}
                    ({{ $theThread->authorName }})
                </a>
            @endif
        </td>
        <td>
            @if ($category->latestActiveThread)
                <?php
                $theThread = $category->threads()->search(request('q'))->latest()->first();

                if (!$theThread) {
                    $theThread = $category->latestActiveThread;
                }
                ?>
                <a href="{{ Forum::route('thread.show', $theThread->lastPost) }}">
                    {{ $theThread->title }}
                    ({{ $theThread->lastPost->authorName }})
                </a>
            @endif
        </td>
    @endif
</tr>
