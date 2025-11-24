@extends('layouts.layout')

@section('title', 'Assessment Results')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="assessmentResults">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Assessment Results</h1>
        <select x-model="selectedAssessment" class="border rounded px-3 py-2">
            <option value="">Select Assessment</option>
            <option value="1">Assessment 1</option>
            <option value="2">Assessment 2</option>
            <!-- dynamically load assessments here -->
        </select>
    </div>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-sm text-gray-500">Total Students</h2>
            <p class="text-xl font-bold" x-text="stats.totalStudents">0</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-sm text-gray-500">Average Score</h2>
            <p class="text-xl font-bold" x-text="stats.avgScore">0</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-sm text-gray-500">Highest Score</h2>
            <p class="text-xl font-bold" x-text="stats.highestScore">0</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-sm text-gray-500">Lowest Score</h2>
            <p class="text-xl font-bold" x-text="stats.lowestScore">0</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-lg font-bold mb-4">Question Performance</h2>
            <canvas id="barChart"></canvas>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-lg font-bold mb-4">Score Distribution</h2>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <!-- Student Table -->
    <div class="bg-white shadow p-4 rounded">
        <h2 class="text-lg font-bold mb-4">Student Scores</h2>
        <table class="min-w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Student</th>
                    <th class="border px-4 py-2">Score</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="student in students" :key="student.id">
                    <tr>
                        <td class="border px-4 py-2" x-text="student.name"></td>
                        <td class="border px-4 py-2" x-text="student.score"></td>
                        <td class="border px-4 py-2" x-text="student.status"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('assessmentResults', () => ({
        selectedAssessment: '',
        stats: {
            totalStudents: 0,
            avgScore: 0,
            highestScore: 0,
            lowestScore: 0
        },
        students: [],
        barChart: null,
        pieChart: null,

        init() {
            this.loadData();

            // Watch for assessment changes
            this.$watch('selectedAssessment', value => {
                if(value) this.loadData();
            });
        },

        loadData() {
            // Dummy data for now
            this.stats = {
                totalStudents: 20,
                avgScore: 78,
                highestScore: 100,
                lowestScore: 45
            };

            this.students = [
                {id:1,name:'Alice',score:90,status:'Passed'},
                {id:2,name:'Bob',score:65,status:'Passed'},
                {id:3,name:'Charlie',score:45,status:'Failed'},
                // more students...
            ];

            // Question-wise performance
            const questionLabels = ['Q1','Q2','Q3','Q4','Q5'];
            const questionData = [80, 60, 90, 70, 50]; // % correct

            // Score distribution
            const scoreLabels = ['0-50','51-70','71-90','91-100'];
            const scoreData = [5,7,6,2];

            // Destroy old charts if exist
            if(this.barChart) this.barChart.destroy();
            if(this.pieChart) this.pieChart.destroy();

            const ctxBar = document.getElementById('barChart').getContext('2d');
            this.barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: questionLabels,
                    datasets: [{
                        label: '% Correct',
                        data: questionData,
                        backgroundColor: 'rgba(37, 99, 235, 0.6)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, max:100 }
                    }
                }
            });

            const ctxPie = document.getElementById('pieChart').getContext('2d');
            this.pieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: scoreLabels,
                    datasets: [{
                        label: 'Students',
                        data: scoreData,
                        backgroundColor: [
                            'rgba(239, 68, 68,0.6)',
                            'rgba(251, 191, 36,0.6)',
                            'rgba(34, 197, 94,0.6)',
                            'rgba(37, 99, 235,0.6)',
                        ]
                    }]
                },
                options: {}
            });
        }
    }))
})
</script>
@endsection
