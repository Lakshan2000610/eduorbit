@extends('layouts.admin')

@section('title', 'Pricing Management')

@section('content')
<div class="max-w-7xl mx-auto p-8 space-y-8">
  <!-- Page Title -->
  <div>
    <h1 class="text-3xl font-bold">Pricing Management</h1>
    <p class="text-gray-600 mt-2">
      Manage hierarchical pricing across grades, subjects, topics, and subtopics
    </p>
  </div>

  <!-- Platform Fee Card -->
  <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-xl font-semibold">Platform Fee</h2>
    </div>
    <div class="p-6 space-y-4">
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Platform Service Fee (%)</label>
        <input id="platform-fee" type="number" min="0" max="100" step="0.1"
               class="border border-gray-300 rounded-md px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $platformFee }}">
        <p class="text-sm text-gray-500">
          This fee applies to all teacher transactions.
        </p>
      </div>
      <button onclick="savePlatformFee()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Save Platform Fee
      </button>
      <p id="platform-msg" class="text-sm mt-2"></p>
    </div>
  </div>

  <!-- Breadcrumb (hidden initially) -->
  <div id="breadcrumb" class="hidden flex items-center gap-2 py-4 px-6 bg-gray-100 rounded-lg"></div>

  <!-- Content Area -->
  <div id="content"></div>
</div>

<script>
  // Use data passed from controller instead of mock
  const pricingData = {grades: @json($grades)};
  let currentData = JSON.parse(JSON.stringify(pricingData)); // deep copy
  let navigation = { level: "grades" };

  // Utility price calculations (unchanged)
  function calcTopicPrices(topic) {
    if (topic.subtopics.length === 0) return { min: 0, max: 0 };
    const mins = topic.subtopics.map(s => s.minPrice);
    const maxs = topic.subtopics.map(s => s.maxPrice);
    return { min: Math.min(...mins), max: Math.max(...maxs) };
  }

  function calcSubjectPrices(subject) {
    if (subject.topics.length === 0) return { min: 0, max: 0 };
    const mins = subject.topics.map(t => calcTopicPrices(t).min);
    const maxs = subject.topics.map(t => calcTopicPrices(t).max);
    return { min: Math.min(...mins), max: Math.max(...maxs) };
  }

  function calcGradePrices(grade) {
    if (grade.subjects.length === 0) return { min: 0, max: 0 };
    const mins = grade.subjects.map(s => calcSubjectPrices(s).min);
    const maxs = grade.subjects.map(s => calcSubjectPrices(s).max);
    return { min: Math.min(...mins), max: Math.max(...maxs) };
  }

  // Render functions (unchanged, but now uses real data)
  function renderGrades() {
    let html = `<div class="space-y-4">
      <h2 class="text-2xl font-semibold text-gray-800">Grades Pricing Summary</h2>
      <div class="border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Subjects</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Min Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Max Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">`;
    currentData.grades.forEach(grade => {
      const prices = calcGradePrices(grade);
      html += `<tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${grade.name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${grade.subjects.length}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${prices.min.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${prices.max.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <button onclick='viewGrade("${grade.code}")' class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">View</button>
        </td>
      </tr>`;
    });
    html += `</tbody></table></div></div>`;
    document.getElementById("content").innerHTML = html;
    document.getElementById("breadcrumb").classList.add("hidden");
  }

  function renderSubjects(grade) {
    let html = `<div class="space-y-4">
      <h2 class="text-2xl font-semibold text-gray-800">Subjects – ${grade.name}</h2>
      <div class="border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Min Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Max Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">`;
    grade.subjects.forEach(subject => {
      const prices = calcSubjectPrices(subject);
      html += `<tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${subject.code}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${subject.name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${prices.min.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${prices.max.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <button onclick='viewSubject("${grade.code}","${subject.code}")' class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">View</button>
        </td>
      </tr>`;
    });
    html += `</tbody></table></div></div>`;
    document.getElementById("content").innerHTML = html;
    updateBreadcrumb([{label:"Grades",click:"navigateToGrades()"},{label:grade.name,click:""}]);
  }

  function renderTopics(subject) {
    let html = `<div class="space-y-4">
      <h2 class="text-2xl font-semibold text-gray-800">Topics – ${subject.name}</h2>
      <div class="border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic Name</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Min Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Max Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">`;
    subject.topics.forEach(topic => {
      const prices = calcTopicPrices(topic);
      html += `<tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${topic.code}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${topic.name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${prices.min.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${prices.max.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <button onclick='viewTopic("${subject.code}","${topic.code}")' class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">View</button>
        </td>
      </tr>`;
    });
    html += `</tbody></table></div></div>`;
    document.getElementById("content").innerHTML = html;
    const grade = getCurrentGrade();
    updateBreadcrumb([
      {label:"Grades",click:"navigateToGrades()"},
      {label:grade.name,click:`viewGrade("${grade.code}")`},
      {label:subject.name,click:""}
    ]);
  }

  function renderSubtopics(topic) {
    let html = `<div class="space-y-4">
      <h2 class="text-2xl font-semibold text-gray-800">Subtopics – ${topic.name}</h2>
      <div class="border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtopic Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtopic Name</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Min Price (LKR)</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Max Price (LKR)</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">`;
    topic.subtopics.forEach(sub => {
      html += `<tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${sub.code}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${sub.name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right" id="min-${sub.code}">${sub.minPrice.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right" id="max-${sub.code}">${sub.maxPrice.toLocaleString()}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sub.status}</td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <button onclick='startEdit("${sub.code}")' id="edit-btn-${sub.code}" class="border border-gray-300 px-3 py-1 rounded text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Edit
          </button>
          <div id="edit-controls-${sub.code}" class="hidden inline-flex gap-1">
            <button onclick='saveEdit("${sub.code}")' class="text-green-600 hover:text-green-700 px-2 focus:outline-none">✓</button>
            <button onclick='cancelEdit("${sub.code}")' class="text-red-600 hover:text-red-700 px-2 focus:outline-none">✗</button>
          </div>
        </td>
      </tr>`;
    });
    html += `</tbody></table></div></div>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
      <p class="text-sm text-blue-900">
        Topic, Subject, and Grade prices are automatically calculated from subtopic prices.
      </p>
    </div>`;
    document.getElementById("content").innerHTML = html;
    const subject = getCurrentSubject();
    const grade = getCurrentGrade();
    updateBreadcrumb([
      {label:"Grades",click:"navigateToGrades()"},
      {label:grade.name,click:`viewGrade("${grade.code}")`},
      {label:subject.name,click:`viewSubject("${grade.code}","${subject.code}")`},
      {label:topic.name,click:""}
    ]);
  }

  function updateBreadcrumb(items) {
    let html = '';
    items.forEach((item, i) => {
      if (i > 0) html += '<span class="text-gray-400 mx-2">›</span>';
      if (item.click) {
        html += `<button onclick='${item.click}' class="text-blue-600 hover:underline text-sm">${item.label}</button>`;
      } else {
        html += `<span class="text-gray-800 text-sm font-medium">${item.label}</span>`;
      }
    });
    const bc = document.getElementById("breadcrumb");
    bc.innerHTML = html;
    bc.classList.remove("hidden");
  }

  // Navigation helpers (unchanged)
  function getCurrentGrade() {
    return currentData.grades.find(g => g.code === navigation.selectedGrade);
  }

  function getCurrentSubject() {
    return getCurrentGrade().subjects.find(s => s.code === navigation.selectedSubject);
  }

  function getCurrentTopic() {
    return getCurrentSubject().topics.find(t => t.code === navigation.selectedTopic);
  }

  function viewGrade(code) {
    navigation = { level: "subjects", selectedGrade: code };
    renderSubjects(currentData.grades.find(g => g.code === code));
  }

  function viewSubject(gradeCode, subjectCode) {
    navigation.selectedSubject = subjectCode;
    const grade = currentData.grades.find(g => g.code === gradeCode);
    renderTopics(grade.subjects.find(s => s.code === subjectCode));
  }

  function viewTopic(subjectCode, topicCode) {
    navigation.selectedTopic = topicCode;
    const subject = getCurrentSubject();
    renderSubtopics(subject.topics.find(t => t.code === topicCode));
  }

  function navigateToGrades() {
    navigation = { level: "grades" };
    renderGrades();
  }

  // Editing subtopics (client-side only; add AJAX for persistence if needed)
// ... (keep existing code above)

// Editing subtopics (now with AJAX)
let editingCode = null;
function startEdit(code) {
  editingCode = code;
  const sub = getCurrentTopic().subtopics.find(s => s.code === code);
  document.getElementById(`min-${code}`).innerHTML = `<input type="number" id="edit-min-${code}" value="${sub.minPrice}" class="border rounded px-2 py-1 w-24 text-right focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">`;
  document.getElementById(`max-${code}`).innerHTML = `<input type="number" id="edit-max-${code}" value="${sub.maxPrice}" class="border rounded px-2 py-1 w-24 text-right focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">`;
  document.getElementById(`edit-btn-${code}`).classList.add("hidden");
  document.getElementById(`edit-controls-${code}`).classList.remove("hidden");
}

function cancelEdit(code) {
  editingCode = null;
  const sub = getCurrentTopic().subtopics.find(s => s.code === code);
  document.getElementById(`min-${code}`).textContent = sub.minPrice.toLocaleString();
  document.getElementById(`max-${code}`).textContent = sub.maxPrice.toLocaleString();
  document.getElementById(`edit-btn-${code}`).classList.remove("hidden");
  document.getElementById(`edit-controls-${code}`).classList.add("hidden");
}

function saveEdit(code) {
  const minInput = document.getElementById(`edit-min-${code}`);
  const maxInput = document.getElementById(`edit-max-${code}`);
  const min = Number(minInput.value);
  const max = Number(maxInput.value);
  if (min < 0 || max < 0) {
    alert("Prices must be positive");
    return;
  }
  if (max <= min) {
    alert("Max price must be greater than min price");
    return;
  }
  const sub = getCurrentTopic().subtopics.find(s => s.code === code);
  fetch("{{ route('admin.subtopic-pricing.update') }}", {  // Adjust route name if no 'admin.' prefix
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
      subtopic_id: sub.id,  // Uses the 'id' passed from controller
      min_price: min,
      max_price: max,
      currency: 'LKR'  // Hardcoded; add input if needed
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Update local data for UI
      sub.minPrice = min;
      sub.maxPrice = max;
      renderSubtopics(getCurrentTopic());  // Refresh to recalc parents
      alert(data.message);
    } else {
      alert('Error: ' + (data.message || 'Failed to save'));
    }
  })
  .catch(error => alert('Error: ' + error));
}

function savePlatformFee() {
  const fee = Number(document.getElementById("platform-fee").value);
  if (fee < 0 || fee > 100) {
    document.getElementById("platform-msg").textContent = "Platform fee must be between 0% and 100%";
    document.getElementById("platform-msg").className = "text-red-600";
    return;
  }
  fetch("{{ route('admin.platform-fee.update') }}", {  // Adjust route name if no 'admin.' prefix
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

// ... (keep existing code below)
  // Initial render
  renderGrades();
</script>

<style>
  input[type="number"] { text-align: right; }
</style>
@endsection