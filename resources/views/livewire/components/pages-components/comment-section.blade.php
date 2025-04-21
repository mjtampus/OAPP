<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-6">Comments</h2>
    
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif
    
    <!-- Add Comment Form -->
    <div class="mb-8">
        <form wire:submit.prevent="addComment" class="space-y-4">
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700">Add a comment</label>
                <div class="mt-1">
                    <textarea 
                        id="comment" 
                        wire:model.defer="newComment" 
                        rows="3" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Share your thoughts..."></textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Post Comment
                </button>
            </div>
        </form>
    </div>
    
    <!-- Comments List -->
    <div class="space-y-6">
        @forelse ($comments as $comment)
            <div class="bg-gray-50 rounded-lg p-4" id="comment-{{ $comment['id'] }}">
                <div class="flex justify-between">
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0">
                        <img
                            class="h-10 w-10 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode($comment['user']['name']) }}"
                            title="{{ $comment['user']['name'] }}"
                            alt="{{ $comment['user']['name'] }}"
                        >                        
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $comment['user']['name'] }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-700">
                    <p>{{ $comment['comment'] }}</p>
                </div>
                <div class="mt-2 flex space-x-4">
                    <button 
                        wire:click="likeComment({{ $comment['id'] }})" 
                        class="flex items-center text-sm {{ auth()->check() && collect($comment['likes'])->where('user_id', auth()->id())->where('comments_id', $comment['id'])->count() > 0 ? 'text-blue-500' : 'text-gray-500' }}
 ">
                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                        </svg>{{ collect($comment['likes'])->where('like_status','like')->where('comments_id', $comment['id'])->count() }}
                    </button>
                    <button 
                        wire:click="startReply({{ $comment['id'] }})" 
                        class="flex items-center text-sm text-gray-500 hover:text-gray-700">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Reply
                    </button>
                </div>
                
                <!-- Reply Form -->
                @if ($replyingTo === $comment['id'])
                    <div class="mt-4 pl-4 border-l-2 border-gray-200">
                        <form wire:submit.prevent="addReply({{ $comment['id'] }})" class="space-y-3">
                            <div>
                                <textarea 
                                    wire:model.defer="replyComment" 
                                    rows="2" 
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Write a reply..."></textarea>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Post Reply
                                </button>
                                <button type="button" wire:click="cancelReply" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
                
                <!-- Replies -->
                @if (count($comment['replies']) > 0)
                    <div class="mt-4 pl-4 border-l-2 border-gray-200 space-y-4">
                        @foreach ($comment['replies'] as $reply)
                            <div class="bg-white rounded-lg p-3">
                                <div class="flex justify-between">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe" alt="John Doe">
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $reply['user']['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reply['created_at'])->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-gray-700">
                                    <p>{{ $reply['comment'] }}</p>
                                </div>
                                <div class="mt-2">
                                <button 
                                    wire:click="likeReply({{ $reply['id'] }})" 
                                    class="flex items-center text-xs
                                        {{ $reply['is_liked'] ? 'text-blue-500 hover:text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                                    
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                    </svg> {{ collect($comment['replies'])->where('is_liked', true)->where('id', $reply['id'])->count() }}                                            
                                </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500">
                No comments yet. Be the first to share your thoughts!
            </div>
        @endforelse
    </div>
</div>
