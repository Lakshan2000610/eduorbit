@extends('layouts.student')

@section('content')
@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
    $user = Auth::user();

    $subjectTopics = $subject->topics ?? collect();
    $progressMap = collect($subjectProgress ?? [])->keyBy('topic_id');
    $initialTopic = $subjectTopics->first();

    // Build simple arrays for JSON including subtopic resources & outcomes
    $topicsForJs = [];
    foreach ($subjectTopics as $t) {
        $subs = [];
        foreach ($t->subtopics ?? collect() as $s) {
            $resArr = [];
            foreach ($s->resources ?? collect() as $r) {
                $resArr[] = [
                    'id' => $r->id,
                    'type' => $r->type,
                    'content' => $r->content ?? '',
                    'url' => $r->url ?? '',
                    'title' => $r->title ?? ( $r->type ? ucfirst($r->type).' Resource' : 'Resource' ), // added title
                ];
            }
            $outArr = [];
            foreach ($s->learningOutcomes ?? collect() as $o) {
                $outArr[] = [
                    'id' => $o->id,
                    'outcome' => $o->outcome,
                    'level' => $o->difficulty_level,
                ];
            }
            $subs[] = [
                'id' => $s->id,
                'name' => $s->subtopic_name,
                'description' => $s->description,
                'resources' => $resArr,
                'learningOutcomes' => $outArr,
            ];
        }
        $topicsForJs[] = [
            'id' => $t->id,
            'name' => $t->topic_name,
            'subtopics' => $subs,
        ];
    }

    $progressForJs = [];
    foreach ($progressMap as $topicId => $p) {
        $progressForJs[$topicId] = $p['value'] ?? 0;
    }
@endphp

<div class="max-w-7xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('student.selected-subjects') }}" class="text-blue-600 hover:underline mb-2 inline-block">
                ← Back to My Learning Roadmap
            </a>
            <h1 class="text-4xl font-bold text-gray-900">{{ $subject->subject_name }} — Progress</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $subject->description ?? ($subject->subject_name.' overview') }}</p>
        </div>

        <div class="text-right">
            <div class="text-xs text-gray-500">Overall Progress</div>
            <div class="text-3xl font-bold text-blue-600">{{ $overallProgress ?? 0 }}%</div>
            <div class="text-sm text-gray-500 mt-1">{{ ($completedTopics ?? 0) }} of {{ ($totalTopics ?? $subjectTopics->count()) }} topics started</div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Left: Topics list -->
        <div class="col-span-4 bg-white rounded-2xl p-4 shadow-sm border border-gray-100 h-[760px] overflow-auto">
            <h2 class="text-lg font-semibold mb-4">Topics</h2>

            @if($subjectTopics->isEmpty())
                <p class="text-sm text-gray-500">No topics available.</p>
            @else
                <div id="topicsList" class="space-y-4">
                    @foreach($subjectTopics as $topic)
                        @php
                            $p = $progressMap->get($topic->id)['value'] ?? 0;
                        @endphp

                        <button
                            type="button"
                            class="topic-item w-full text-left group flex items-center justify-between gap-3 p-3 rounded-xl hover:bg-gray-50 transition {{ $loop->first ? 'bg-gray-50' : '' }}"
                            data-topic-id="{{ $topic->id }}"
                        >
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="font-medium text-gray-900">{{ $topic->topic_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $p }}%</div>
                                </div>

                                <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600" style="width: {{ $p }}%"></div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2 ml-3">
                                <a href="#"
                                   class="find-teacher-btn inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700"
                                   data-topic-id="{{ $topic->id }}"
                                >
                                    Find teacher
                                </a>

                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Subtopics + contents -->
        <div class="col-span-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 h-[760px] overflow-auto">
                <div id="topicHeader" class="mb-4">
                    @if($initialTopic)
                        <h3 id="selectedTopicName" class="text-2xl font-semibold text-gray-900">{{ $initialTopic->topic_name }}</h3>
                        <p id="selectedTopicMeta" class="text-sm text-gray-500 mt-1">{{ ($progressMap->get($initialTopic->id)['value'] ?? 0) }}% — {{ ($initialTopic->subtopics->count() ?? 0) }} subtopic(s)</p>
                    @else
                        <h3 class="text-2xl font-semibold text-gray-900">No topic selected</h3>
                    @endif
                </div>

                <div id="subtopicsContainer">
                    @if($initialTopic)
                        @foreach($initialTopic->subtopics ?? collect() as $sub)
                            <div class="border-b py-4 last:border-b-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $sub->subtopic_name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ $sub->description ?? 'Subtopic details' }}</p>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <button class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium find-teacher-btn" data-subtopic-id="{{ $sub->id }}">Find teacher</button>
                                        <button class="toggle-contents inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-50 text-gray-600" data-subtopic-id="{{ $sub->id }}" aria-expanded="false">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div id="contents-{{ $sub->id }}" class="mt-4 hidden space-y-3">
                                    <!-- Resources Section -->
                                    @if($sub->resources && count($sub->resources) > 0)
                                        <div>
                                            <h5 class="text-sm font-semibold text-gray-700 mb-3">Resources</h5>
                                            <div class="space-y-3">
                                                @foreach($sub->resources as $resource)
                                                    <div class="resource-item border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition">
                                                        <div class="flex items-center justify-between mb-3">
                                                            <div class="flex items-center gap-3">
                                                                @if($resource->type === 'video')
                                                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                                                                @elseif($resource->type === 'image')
                                                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                                                                @else
                                                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                                                @endif
                                                                <span class="font-medium text-gray-900">{{ $resource->title ?? ucfirst($resource->type).' Resource' }}</span>
                                                            </div>
                                                            <button class="mark-resource-btn px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition" data-resource-id="{{ $resource->id }}" data-resource-type="{{ ucfirst($resource->type) }}">
                                                                <span class="btn-text">Mark Done</span>
                                                                <span class="btn-icon" style="display:none;">✓</span>
                                                            </button>
                                                        </div>

                                                        <!-- Resource Content Display -->
                                                        @if($resource->type === 'text')
                                                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded border-l-4 border-green-500">
                                                                {{ $resource->content }}
                                                            </div>
                                                        @elseif($resource->type === 'video')
                                                            <div class="mt-2">
                                                                @if(is_string($resource->url) && Str::endsWith($resource->url, ['.mp4', '.webm', '.ogg']))
                                                                    <video class="w-full rounded-lg bg-black max-h-64" controls>
                                                                        <source src="{{ asset('storage/' . $resource->url) }}" type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                @else
                                                                    <div class="text-sm text-gray-600 bg-gray-100 p-3 rounded">
                                                                        <p><strong>Video URL:</strong></p>
                                                                        <a href="{{ is_string($resource->url) ? $resource->url : '#' }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ is_string($resource->url) ? $resource->url : 'Invalid resource' }}</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @elseif($resource->type === 'image')
                                                            <div class="mt-2">
                                                                @if(is_string($resource->url) && Str::endsWith($resource->url, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                                                    <img src="{{ asset('storage/' . $resource->url) }}" alt="Resource Image" class="w-full rounded-lg max-h-96 object-contain bg-gray-100">
                                                                @else
                                                                    <div class="text-sm text-gray-600 bg-gray-100 p-3 rounded">
                                                                        <p><strong>Image URL:</strong></p>
                                                                        <a href="{{ is_string($resource->url) ? $resource->url : '#' }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ is_string($resource->url) ? $resource->url : 'Invalid resource' }}</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500">No resources available.</div>
                                    @endif

                                    <!-- Learning Outcomes Section -->
                                    @if($sub->learningOutcomes && count($sub->learningOutcomes) > 0)
                                        <div class="mt-4">
                                            <h5 class="text-sm font-semibold text-gray-700 mb-2">Learning Outcomes</h5>
                                            <ul class="space-y-1">
                                                @foreach($sub->learningOutcomes as $outcome)
                                                    <li class="flex items-start gap-2 text-sm text-gray-700">
                                                        <span class="text-blue-500 mt-1">•</span>
                                                        <span>{{ $outcome->outcome }}<br><small class="text-gray-500">({{ ucfirst($outcome->difficulty_level) }})</small></span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-sm text-gray-500">Select a topic from the left to view subtopics.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart (small) -->
    <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h4 class="font-semibold mb-4">Topic progress chart</h4>
        <div class="h-48">
            <canvas id="topicsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const topicButtons = document.querySelectorAll('.topic-item');
    const topics = @json($topicsForJs);
    const progressMap = @json($progressForJs);
    const topicsList = document.getElementById('topicsList');
    const subtopicsContainer = document.getElementById('subtopicsContainer');

    function renderResourceHTML(resource) {
        const title = resource.title || (resource.type.charAt(0).toUpperCase() + resource.type.slice(1) + ' Resource');
        let html = `<div class="resource-item border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">`;

        const icons = {
            'video': '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>',
            'image': '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>',
            'text': '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>'
        };

        html += (icons[resource.type] || icons['text']) + `<span class="font-medium text-gray-900">${title}</span>
                            </div>
                            <button class="mark-resource-btn px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition" data-resource-id="${resource.id}" data-resource-type="${resource.type}">
                                <span class="btn-text">Mark Done</span>
                                <span class="btn-icon" style="display:none;">✓</span>
                            </button>
                        </div>`;

        if (resource.type === 'text') {
            html += `<div class="text-sm text-gray-700 bg-gray-50 p-3 rounded border-l-4 border-green-500">${resource.content}</div>`;
        } else if (resource.type === 'video') {
            if (typeof resource.url === 'string' && (resource.url.endsWith('.mp4') || resource.url.endsWith('.webm') || resource.url.endsWith('.ogg'))) {
                html += `<div class="mt-2"><video class="w-full rounded-lg bg-black max-h-64" controls><source src="/storage/${resource.url}" type="video/mp4">Your browser does not support the video tag.</video></div>`;
            } else {
                html += `<div class="mt-2 text-sm text-gray-600 bg-gray-100 p-3 rounded"><p><strong>Video URL:</strong></p><a href="${resource.url}" target="_blank" class="text-blue-600 hover:underline break-all">${resource.url}</a></div>`;
            }
        } else if (resource.type === 'image') {
            if (typeof resource.url === 'string' && (resource.url.endsWith('.jpg') || resource.url.endsWith('.jpeg') || resource.url.endsWith('.png') || resource.url.endsWith('.gif') || resource.url.endsWith('.webp'))) {
                html += `<div class="mt-2"><img src="/storage/${resource.url}" alt="Resource Image" class="w-full rounded-lg max-h-96 object-contain bg-gray-100"></div>`;
            } else {
                html += `<div class="mt-2 text-sm text-gray-600 bg-gray-100 p-3 rounded"><p><strong>Image URL:</strong></p><a href="${resource.url}" target="_blank" class="text-blue-600 hover:underline break-all">${resource.url}</a></div>`;
            }
        }

        html += `</div>`;
        return html;
    }

    function renderSubtopics(topicId) {
        const topic = topics.find(t => t.id == topicId);
        if (!topic) {
            subtopicsContainer.innerHTML = '<div class="text-sm text-gray-500">No subtopics found.</div>';
            return;
        }

        document.getElementById('selectedTopicName').textContent = topic.name;
        const prog = progressMap[topicId] ?? 0;
        document.getElementById('selectedTopicMeta').textContent = prog + '% — ' + (topic.subtopics ? topic.subtopics.length : 0) + ' subtopic(s)';

        let html = '';
        if (topic.subtopics && topic.subtopics.length) {
            topic.subtopics.forEach(st => {
                html += `<div class="border-b py-4 last:border-b-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="font-medium text-gray-900">${st.name}</div>
                            <div class="text-sm text-gray-500 mt-1">${st.description ?? 'Subtopic details'}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium find-teacher-btn" data-subtopic-id="${st.id}">Find teacher</button>
                            <button class="toggle-contents inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-50 text-gray-600" data-subtopic-id="${st.id}" aria-expanded="false">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </div>

                    <div id="contents-${st.id}" class="mt-4 hidden space-y-3">`;

                // Resources
                if (st.resources && st.resources.length) {
                    html += `<div><h5 class="text-sm font-semibold text-gray-700 mb-3">Resources</h5><div class="space-y-3">`;
                    st.resources.forEach(r => {
                        html += renderResourceHTML(r);
                    });
                    html += `</div></div>`;
                } else {
                    html += `<div class="text-sm text-gray-500">No resources available.</div>`;
                }

                // Learning outcomes
                if (st.learningOutcomes && st.learningOutcomes.length) {
                    html += `<div class="mt-4"><h5 class="text-sm font-semibold text-gray-700 mb-2">Learning Outcomes</h5><ul class="space-y-1">`;
                    st.learningOutcomes.forEach(o => {
                        html += `<li class="flex items-start gap-2 text-sm text-gray-700">
                                    <span class="text-blue-500 mt-1">•</span>
                                    <span>${o.outcome}<br><small class="text-gray-500">(${(o.level || 'medium').toUpperCase()})</small></span>
                                 </li>`;
                    });
                    html += `</ul></div>`;
                }

                html += `</div></div>`;
            });
        } else {
            html = '<div class="text-sm text-gray-500">No subtopics for this topic.</div>';
        }

        subtopicsContainer.innerHTML = html;
        attachToggles();
        attachFindTeacherHandlers();
        attachResourceHandlers();
    }

    function clearTopicSelection() {
        topicButtons.forEach(btn => btn.classList.remove('bg-gray-50'));
    }

    topicButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            clearTopicSelection();
            this.classList.add('bg-gray-50');
            renderSubtopics(this.dataset.topicId);
        });
    });

    function attachToggles() {
        document.querySelectorAll('.toggle-contents').forEach(btn => {
            btn.removeEventListener('click', toggleHandler);
            btn.addEventListener('click', toggleHandler);
        });
    }

    function toggleHandler(e) {
        const id = this.dataset.subtopicId;
        const el = document.getElementById('contents-' + id);
        if (!el) return;
        const expanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', (!expanded).toString());
        el.classList.toggle('hidden');
    }

    function attachFindTeacherHandlers() {
        document.querySelectorAll('.find-teacher-btn').forEach(btn => {
            btn.removeEventListener('click', findTeacherHandler);
            btn.addEventListener('click', findTeacherHandler);
        });
    }

    function findTeacherHandler(e) {
        e.preventDefault();
        const topicId = this.dataset.topicId;
        const subtopicId = this.dataset.subtopicId;
        let url = '/teachers/search?subject={{ $subject->id }}';
        if (topicId) url += '&topic=' + topicId;
        if (subtopicId) url += '&subtopic=' + subtopicId;
        window.open(url, '_blank');
    }

    function attachResourceHandlers() {
        document.querySelectorAll('.mark-resource-btn').forEach(btn => {
            btn.removeEventListener('click', markResourceHandler);
            btn.addEventListener('click', markResourceHandler);
        });
    }

    function markResourceHandler(e) {
        e.preventDefault();
        const btn = this;
        const resourceId = btn.dataset.resourceId;
        const resourceType = btn.dataset.resourceType;

        fetch(`/student/resource/${resourceId}/mark-complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-icon').style.display = 'inline';
            btn.closest('.resource-item').classList.add('opacity-60');

            // determine topicId client-side from topics array
            const topicId = findTopicIdByResourceId(resourceId);

            // data may be a number or an object. prefer structured object if provided.
            if (typeof data === 'number') {
                updateProgressBar(data, topicId);
                showNotification(`${resourceType} marked as completed! Progress: ${data}%`);
            } else if (data && typeof data === 'object') {
                // backend should ideally return { overall: X, topic_id: Y, topic_progress: Z }
                updateProgressBar(data, topicId);
                const overall = data.overall ?? data.progress ?? null;
                showNotification(`${resourceType} marked as completed! Progress: ${overall ?? 'updated' }%`);
            } else {
                showNotification('Marked completed', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error marking resource as completed', 'error');
        });
    }

    function findTopicIdByResourceId(resourceId) {
        for (const topic of topics) {
            if (!topic.subtopics) continue;
            for (const st of topic.subtopics) {
                if (!st.resources) continue;
                if (st.resources.some(r => r.id == resourceId)) {
                    return topic.id;
                }
            }
        }
        return null;
    }

    // updateProgressBar accepts either:
    // - number (overall progress)
    // - object { overall, topicId, topicProgress } OR second param topicId (when server returned only overall)
    function updateProgressBar(payload, topicIdFromCall = null) {
        let overall = null;
        let topicId = null;
        let topicProgress = null;

        if (typeof payload === 'number') {
            overall = payload;
            topicId = topicIdFromCall;
        } else if (payload && typeof payload === 'object') {
            overall = payload.overall ?? payload.progress ?? null;
            topicId = payload.topic_id ?? payload.topicId ?? topicIdFromCall;
            topicProgress = payload.topic_progress ?? payload.topicProgress ?? null;
        }

        // update overall indicator
        const overallText = document.querySelector('.text-3xl.font-bold.text-blue-600');
        if (overallText && overall !== null) {
            overallText.textContent = overall + '%';
        }

        // update a specific topic progress on the left list when we have topicId
        if (!topicId) return;

        const topicBtn = document.querySelector(`.topic-item[data-topic-id="${topicId}"]`);
        if (topicBtn) {
            // update the numeric percent text
            const percentEl = topicBtn.querySelector('.text-sm.text-gray-500') || topicBtn.querySelector('div > .text-sm.text-gray-500');
            const newPercent = (topicProgress !== null) ? topicProgress : (overall !== null ? overall : null);
            if (newPercent !== null) {
                // find the small percent element (we used .text-sm.text-gray-500 inside the topic button)
                // but safer: find the right node inside the button
                const rightPercent = topicBtn.querySelector('div > .flex.items-center .text-sm.text-gray-500') || topicBtn.querySelector('.flex-1 .flex.items-center .text-sm.text-gray-500') || topicBtn.querySelector('.text-sm.text-gray-500');
                if (rightPercent) rightPercent.textContent = newPercent + '%';
                // update inner progress bar width
                const progressInner = topicBtn.querySelector('.h-2.bg-gradient-to-r') || topicBtn.querySelector('.h-2 > div');
                if (progressInner) progressInner.style.width = newPercent + '%';
            }
        }

        // if selected topic is the same, update header meta
        const selMeta = document.getElementById('selectedTopicMeta');
        if (selMeta && topicProgress !== null) {
            selMeta.textContent = `${topicProgress}% — ${selMeta.textContent.split('—').pop().trim()}`;
        } else if (selMeta && overall !== null && topicId && document.getElementById('selectedTopicName')) {
            // if selected topic equals the updated one, update meta number
            const selTopicId = document.querySelector('.topic-item.bg-gray-50')?.dataset?.topicId;
            if (selTopicId && selTopicId == topicId) {
                selMeta.textContent = `${overall}% — ${selMeta.textContent.split('—').pop().trim()}`;
            }
        }
    }

    attachResourceHandlers();

    @if($initialTopic)
        renderSubtopics({{ $initialTopic->id }});
    @endif

    const ctx = document.getElementById('topicsChart');
    if (ctx) {
        const labels = @json($subjectTopics->pluck('topic_name'));
        const data = @json($subjectTopics->map(fn($t) => $progressMap->get($t->id)['value'] ?? 0));
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Progress (%)',
                    data,
                    backgroundColor: '#3B82F6',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
                }
            }
        });
    }
});
</script>
@endsection