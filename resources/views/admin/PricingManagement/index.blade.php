@extends('layouts.admin')

@section('title', 'Pricing Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto p-8 space-y-8">

        <!-- Page Title -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pricing Management</h1>
            <p class="mt-2 text-gray-600">
                Manage hierarchical pricing across grades, subjects, topics, and subtopics
            </p>
        </div>

        <!-- Platform Fee Card -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold">Platform Fee</h2>
            </div>
            <div class="p-6 space-y-4">
                <form action="{{ route('admin.platform-fee.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label for="platform-fee" class="block text-sm font-medium text-gray-700">
                            Platform Service Fee (%)
                        </label>
                        <input
                            id="platform-fee"
                            name="fee_percentage"
                            type="number"
                            min="0"
                            max="100"
                            step="0.1"
                            value="{{ old('fee_percentage', $platformFee) }}"
                            class="border border-gray-300 rounded-md px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <p class="text-sm text-gray-600">
                            This fee applies to all teacher transactions.
                        </p>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Save Platform Fee
                    </button>
                </form>
                @if(session('success'))
                    <p class="mt-2 text-green-600 text-sm">{{ session('success') }}</p>
                @endif
                @if(session('error'))
                    <p class="mt-2 text-red-600 text-sm">{{ session('error') }}</p>
                @endif
            </div>
        </div>

        <!-- Breadcrumb -->
        <div id="breadcrumb" class="hidden flex items-center gap-2 py-4 px-6 bg-gray-100 rounded-lg text-sm"></div>

        <!-- Content Area -->
        <div id="content"></div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
<style>
    input[type="number"] { text-align: right; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.75rem; border-bottom: 1px solid #e5e7eb; }
    th { background-color: #f9fafb; text-align: left; }
    .text-right { text-align: right; }
    .text-muted-foreground { color: #6b7280; }
    .text-foreground { color: #111827; }
</style>

<script>
    // Real data from Laravel controller
    const pricingData = @json($grades);

    let currentData = { grades: pricingData };
    let navigation = { level: "grades" };

    // Price calculation functions
    function calcTopicPrices(topic) {
        if (!topic.subtopics || topic.subtopics.length === 0) return { min: 0, max: 0 };
        const mins = topic.subtopics.map(s => s.minPrice || 0);
        const maxs = topic.subtopics.map(s => s.maxPrice || 0);
        return { min: Math.min(...mins), max: Math.max(...maxs) };
    }

    function calcSubjectPrices(subject) {
        if (!subject.topics || subject.topics.length === 0) return { min: 0, max: 0 };
        const mins = subject.topics.map(t => calcTopicPrices(t).min);
        const maxs = subject.topics.map(t => calcTopicPrices(t).max);
        return { min: Math.min(...mins), max: Math.max(...maxs) };
    }

    function calcGradePrices(grade) {
        if (!grade.subjects || grade.subjects.length === 0) return { min: 0, max: 0 };
        const mins = grade.subjects.map(s => calcSubjectPrices(s).min);
        const maxs = grade.subjects.map(s => calcSubjectPrices(s).max);
        return { min: Math.min(...mins), max: Math.max(...maxs) };
    }

    // Render functions
    function renderGrades() {
        let html = `
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold">Grades Pricing Summary</h2>
            <div class="border rounded-lg overflow-hidden">
                <table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Grade</th>
                            <th>Total Subjects</th>
                            <th class="text-right">Min Price (LKR)</th>
                            <th class="text-right">Max Price (LKR)</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>`;
        currentData.grades.forEach(grade => {
            const prices = calcGradePrices(grade);
            html += `
                        <tr>
                            <td>${grade.name}</td>
                            <td>${grade.subjects.length}</td>
                            <td class="text-right text-muted-foreground">${prices.min.toLocaleString()}</td>
                            <td class="text-right text-muted-foreground">${prices.max.toLocaleString()}</td>
                            <td class="text-right">
                                <button onclick='viewGrade("${grade.code}")' class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100">View</button>
                            </td>
                        </tr>`;
        });
        html += `</tbody></table></div></div>`;
        document.getElementById("content").innerHTML = html;
        document.getElementById("breadcrumb").classList.add("hidden");
    }

    function renderSubjects(grade) {
        let html = `
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold">Subjects – ${grade.name}</h2>
            <div class="border rounded-lg overflow-hidden">
                <table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th class="text-right">Min Price (LKR)</th>
                            <th class="text-right">Max Price (LKR)</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>`;
        grade.subjects.forEach(subject => {
            const prices = calcSubjectPrices(subject);
            html += `
                        <tr>
                            <td>${subject.code}</td>
                            <td>${subject.name}</td>
                            <td class="text-right text-muted-foreground">${prices.min.toLocaleString()}</td>
                            <td class="text-right text-muted-foreground">${prices.max.toLocaleString()}</td>
                            <td class="text-right">
                                <button onclick='viewSubject("${grade.code}","${subject.code}")' class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100">View</button>
                            </td>
                        </tr>`;
        });
        html += `</tbody></table></div></div>`;
        document.getElementById("content").innerHTML = html;
        updateBreadcrumb([
            {label: "Grades", click: "navigateToGrades()"},
            {label: grade.name, click: ""}
        ]);
    }

    function renderTopics(subject) {
        let html = `
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold">Topics – ${subject.name}</h2>
            <div class="border rounded-lg overflow-hidden">
                <table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Topic Code</th>
                            <th>Topic Name</th>
                            <th class="text-right">Min Price (LKR)</th>
                            <th class="text-right">Max Price (LKR)</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>`;
        subject.topics.forEach(topic => {
            const prices = calcTopicPrices(topic);
            html += `
                        <tr>
                            <td>${topic.code}</td>
                            <td>${topic.name}</td>
                            <td class="text-right text-muted-foreground">${prices.min.toLocaleString()}</td>
                            <td class="text-right text-muted-foreground">${prices.max.toLocaleString()}</td>
                            <td class="text-right">
                                <button onclick='viewTopic("${subject.code}","${topic.code}")' class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100">View</button>
                            </td>
                        </tr>`;
        });
        html += `</tbody></table></div></div>`;
        document.getElementById("content").innerHTML = html;
        const grade = getCurrentGrade();
        updateBreadcrumb([
            {label: "Grades", click: "navigateToGrades()"},
            {label: grade.name, click: `viewGrade("${grade.code}")`},
            {label: subject.name, click: ""}
        ]);
    }

    function renderSubtopics(topic) {
        let html = `
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold">Subtopics – ${topic.name}</h2>
            <div class="border rounded-lg overflow-hidden">
                <table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Subtopic Code</th>
                            <th>Subtopic Name</th>
                            <th class="text-right">Min Price (LKR)</th>
                            <th class="text-right">Max Price (LKR)</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>`;
        topic.subtopics.forEach(sub => {
            html += `
                        <tr>
                            <td>${sub.code}</td>
                            <td>${sub.name}</td>
                            <td class="text-right" id="min-${sub.code}">${(sub.minPrice || 0).toLocaleString()}</td>
                            <td class="text-right" id="max-${sub.code}">${(sub.maxPrice || 0).toLocaleString()}</td>
                            <td>${sub.status || 'Active'}</td>
                            <td class="text-right">
                                <button onclick='startEdit("${sub.code}")' id="edit-btn-${sub.code}" class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100">
                                    Edit
                                </button>
                                <div id="edit-controls-${sub.code}" class="hidden inline-flex gap-1">
                                    <button onclick='saveEdit("${sub.code}")' class="text-green-600 px-2 font-bold">✓</button>
                                    <button onclick='cancelEdit("${sub.code}")' class="text-red-600 px-2 font-bold">✗</button>
                                </div>
                            </td>
                        </tr>`;
        });
        html += `</tbody></table></div></div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-900">
                Topic, Subject, and Grade prices are automatically calculated from subtopic prices.
            </p>
        </div>`;
        document.getElementById("content").innerHTML = html;
        const subject = getCurrentSubject();
        const grade = getCurrentGrade();
        updateBreadcrumb([
            {label: "Grades", click: "navigateToGrades()"},
            {label: grade.name, click: `viewGrade("${grade.code}")`},
            {label: subject.name, click: `viewSubject("${grade.code}","${subject.code}")`},
            {label: topic.name, click: ""}
        ]);
    }

    function updateBreadcrumb(items) {
        let html = '';
        items.forEach((item, i) => {
            if (i > 0) html += '<span class="text-gray-500 mx-2">›</span>';
            if (item.click) {
                html += `<button onclick='${item.click}' class="text-blue-600 hover:underline">${item.label}</button>`;
            } else {
                html += `<span class="text-foreground font-medium">${item.label}</span>`;
            }
        });
        const bc = document.getElementById("breadcrumb");
        bc.innerHTML = html;
        bc.classList.remove("hidden");
    }

    // Navigation
    function getCurrentGrade() {
        return currentData.grades.find(g => g.code === navigation.selectedGrade);
    }
    function getCurrentSubject() {
        return getCurrentGrade()?.subjects.find(s => s.code === navigation.selectedSubject);
    }
    function getCurrentTopic() {
        return getCurrentSubject()?.topics.find(t => t.code === navigation.selectedTopic);
    }

    function viewGrade(code) {
        navigation = { level: "subjects", selectedGrade: code };
        const grade = currentData.grades.find(g => g.code === code);
        renderSubjects(grade);
    }

    function viewSubject(gradeCode, subjectCode) {
        navigation.selectedSubject = subjectCode;
        const grade = currentData.grades.find(g => g.code === gradeCode);
        const subject = grade.subjects.find(s => s.code === subjectCode);
        renderTopics(subject);
    }

    function viewTopic(subjectCode, topicCode) {
        navigation.selectedTopic = topicCode;
        const subject = getCurrentSubject();
        const topic = subject.topics.find(t => t.code === topicCode);
        renderSubtopics(topic);
    }

    function navigateToGrades() {
        navigation = { level: "grades" };
        renderGrades();
    }

    // Editing
    function startEdit(code) {
        const sub = getCurrentTopic().subtopics.find(s => s.code === code);
        document.getElementById(`min-${code}`).innerHTML = `<input type="number" id="edit-min-${code}" value="${sub.minPrice || 0}" class="border rounded px-2 py-1 w-32 text-right" min="0">`;
        document.getElementById(`max-${code}`).innerHTML = `<input type="number" id="edit-max-${code}" value="${sub.maxPrice || 0}" class="border rounded px-2 py-1 w-32 text-right" min="0">`;
        document.getElementById(`edit-btn-${code}`).classList.add("hidden");
        document.getElementById(`edit-controls-${code}`).classList.remove("hidden");
    }

    function cancelEdit(code) {
        const sub = getCurrentTopic().subtopics.find(s => s.code === code);
        document.getElementById(`min-${code}`).textContent = (sub.minPrice || 0).toLocaleString();
        document.getElementById(`max-${code}`).textContent = (sub.maxPrice || 0).toLocaleString();
        document.getElementById(`edit-btn-${code}`).classList.remove("hidden");
        document.getElementById(`edit-controls-${code}`).classList.add("hidden");
    }

   function savePlatformFee() {
    const fee = Number(document.getElementById("platform-fee").value);
    if (fee < 0 || fee > 100) {
        document.getElementById("platform-msg").textContent = "Platform fee must be between 0% and 100%";
        document.getElementById("platform-msg").className = "text-red-600";
        return;
    }
    fetch('{{ route("admin.platform-fee.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ fee_percentage: fee })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("platform-msg").textContent = data.message;
        document.getElementById("platform-msg").className = data.success ? "text-green-600" : "text-red-600";
    })
    .catch(error => {
        document.getElementById("platform-msg").textContent = "Error: " + error;
        document.getElementById("platform-msg").className = "text-red-600";
    });
}

    // Initial load
    renderGrades();
</script>

@endsection