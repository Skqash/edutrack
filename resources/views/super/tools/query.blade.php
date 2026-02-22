@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="mb-6">
            <a href="{{ route('super.dashboard') }}" class="text-blue-600 hover:underline">&larr; Back to Dashboard</a>
            <h1 class="text-3xl font-bold mt-2">Database Query Runner</h1>
            <p class="text-gray-600 mt-2">⚠️ <strong>READ-ONLY:</strong> Only SELECT queries are allowed for security.</p>
        </div>

        @if ($message = session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">{{ $message }}</div>
        @endif

        <div class="bg-white p-6 rounded shadow mb-6">
            <form action="{{ route('super.tools.query.run') }}" method="POST">
                @csrf
                <label class="block font-bold mb-2">SQL Query (SELECT only)</label>
                <textarea name="sql" class="w-full border px-3 py-2 rounded font-mono mb-4" rows="6"
                    placeholder="SELECT * FROM users WHERE role = 'teacher';" required>{{ $sql ?? '' }}</textarea>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-bold">Run
                    Query</button>
            </form>
        </div>

        @if (isset($results))
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Results ({{ count($results) }} rows)</h2>

                @if (count($results) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-sm">
                            <thead class="bg-gray-200 border">
                                <tr>
                                    @foreach ((array) $results[0] as $key => $value)
                                        <th class="p-2 border text-left">{{ $key }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $row)
                                    <tr class="border hover:bg-gray-50">
                                        @foreach ((array) $row as $value)
                                            <td class="p-2 border">{{ $value ?? 'NULL' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">No results found.</p>
                @endif
            </div>
        @endif
    </div>
@endsection
