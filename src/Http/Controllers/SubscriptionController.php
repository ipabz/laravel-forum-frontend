<?php namespace Riari\Forum\Frontend\Http\Controllers;

use Forum;
use Illuminate\Http\Request;
use Riari\Forum\Models\Category;

class SubscriptionController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe(Request $request)
    {
        $type = $request->has('thread') ? 'Riari\Forum\Models\Thread' : 'Riari\Forum\Models\Category';
        $id = $request->exists('thread') ? $request->input('thread') : $request->input('category');

        $subscription = $this->api('subscription.subscribe')->parameters([
            'subscribable_id' => $id,
            'subscribable_type' => $type,
            'user_id' => auth()->user()->getKey(),
        ])->post();

        Forum::alert('success', 'subscriptions.success_subscribed');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsubscribe(Request $request)
    {
        $type = $request->has('thread') ? 'Riari\Forum\Models\Thread' : 'Riari\Forum\Models\Category';
        $id = $request->exists('thread') ? $request->input('thread') : $request->input('category');

        $subscription = $this->api('subscription.unsubscribe')->parameters([
            'subscribable_id' => $id,
            'subscribable_type' => $type,
            'user_id' => auth()->user()->getKey(),
        ])->post();

        Forum::alert('success', 'subscriptions.success_unsubscribed');

        return redirect()->back();
    }

    public function unsubscribe_from_email($subsIdEncrypted)
    {
        $response = $this->api('subscription.unsubscribe_email')->parameters([
            'subscription_id_encrypted' => $subsIdEncrypted
        ])->post();

        if ($response['success']) {
            $url = Forum::route('thread.show', $response['subscribable']);

            Forum::alert('success', 'subscriptions.success_unsubscribed');
        } else {
            $url = url('forum');
        }

        return redirect($url);
    }

}
