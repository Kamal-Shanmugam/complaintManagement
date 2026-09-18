<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complaint Desk</title>
    <style>
        :root {
            font-family: Arial, sans-serif;
            color: #1f2937;
            background: #f3f4f6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 20px 48px;
        }

        header {
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
        }

        header p {
            margin: 6px 0 0;
            color: #6b7280;
        }

        .layout {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 24px;
            align-items: start;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 2px #00000008;
        }

        h2 {
            margin: 0 0 18px;
            font-size: 18px;
        }

        .field {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 9px 10px;
            font: inherit;
            font-size: 14px;
            background: #fff;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .error {
            display: block;
            color: #b91c1c;
            font-size: 12px;
            margin-top: 4px;
        }

        button,
        .button {
            border: 0;
            border-radius: 6px;
            padding: 9px 12px;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .primary {
            background: #2563eb;
            color: #fff;
        }

        .secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px 14px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .filters {
            display: grid;
            grid-template-columns: 1fr 150px 160px auto;
            gap: 10px;
            margin-bottom: 18px;
        }

        .filters button {
            align-self: end;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
            margin-top: 3px;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .priority-low,
        .status-resolved {
            background: #dcfce7;
            color: #166534;
        }

        .priority-medium,
        .status-progress {
            background: #fef3c7;
            color: #92400e;
        }

        .priority-high,
        .status-open {
            background: #fee2e2;
            color: #b91c1c;
        }

        .actions {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .actions button,
        .actions .button {
            padding: 6px 8px;
            font-size: 12px;
        }

        .actions form {
            margin: 0;
        }

        .empty {
            color: #6b7280;
            text-align: center;
            padding: 32px;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
            font-size: 14px;
        }

        .pagination .button[aria-disabled="true"] {
            opacity: .4;
            pointer-events: none;
        }

        .form-actions {
            display: flex;
            gap: 8px;
        }

        @media (max-width: 850px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 500px) {
            .filters {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px 12px;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <header>
            <h1>Complaint Desk</h1>
            <p>A simple customer complaint tracker.</p>
        </header>

        @if (session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        <div class="layout">
            <aside class="card">
                <h2>{{ $editingComplaint ? 'Edit complaint' : 'Add complaint' }}</h2>
                <form method="POST" action="{{ $editingComplaint ? route('complaints.update', $editingComplaint) : route('complaints.store') }}">
                    @csrf
                    @if ($editingComplaint) @method('PUT') @endif

                    <div class="field">
                        <label for="customer_name">Customer name</label>
                        <input id="customer_name" name="customer_name" value="{{ old('customer_name', $editingComplaint?->customer_name) }}" required>
                        @error('customer_name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $editingComplaint?->email) }}" required>
                        @error('email') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="subject">Subject</label>
                        <input id="subject" name="subject" value="{{ old('subject', $editingComplaint?->subject) }}" required>
                        @error('subject') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required>{{ old('description', $editingComplaint?->description) }}</textarea>
                        @error('description') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" required>
                            @foreach (['Low', 'Medium', 'High'] as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', $editingComplaint?->priority ?? 'Medium') === $priority)>{{ $priority }}</option>
                            @endforeach
                        </select>
                        @error('priority') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            @foreach (['Open', 'In Progress', 'Resolved'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $editingComplaint?->status ?? 'Open') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-actions">
                        <button class="primary" type="submit">{{ $editingComplaint ? 'Save changes' : 'Add complaint' }}</button>
                        @if ($editingComplaint)<a class="button secondary" href="{{ route('complaints.index') }}">Cancel</a>@endif
                    </div>
                </form>
            </aside>

            <section class="card">
                <h2>All complaints</h2>
                <form class="filters" method="GET" action="{{ route('complaints.index') }}">
                    <div><label for="search">Search</label>
                        <input id="search" name="search" value="{{ request('search') }}" placeholder="Customer or subject">
                    </div>
                    <div><label for="filter-priority">Priority</label><select id="filter-priority" name="priority">
                            <option value="">All priorities</option>
                            @foreach (['Low', 'Medium', 'High'] as $priority)
                            <option value="{{ $priority }}" @selected(request('priority')===$priority)>{{ $priority }}</option>
                            @endforeach
                        </select></div>
                    <div>
                        <label for="filter-status">Status</label>
                        <select id="filter-status" name="status">
                            <option value="">All statuses</option>
                            @foreach (['Open', 'In Progress', 'Resolved'] as $status)
                            <option value="{{ $status }}"
                                @selected(request('status')===$status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="primary" type="submit">Filter</button>
                </form>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Complaint</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($complaints as $complaint)
                            <tr>
                                <td>#{{ $complaint->id }}</td>
                                <td><strong>{{ $complaint->customer_name }}</strong>
                                    <div class="muted">{{ $complaint->email }}</div>
                                </td>
                                <td><strong>{{ $complaint->subject }}</strong>
                                    <div class="muted">{{ \Illuminate\Support\Str::limit($complaint->description, 65) }}</div>
                                </td>
                                <td><span class="badge priority-{{ strtolower($complaint->priority) }}">{{ $complaint->priority }}</span></td>
                                <td><span class="badge {{ $complaint->status === 'In Progress' ? 'status-progress' : 'status-'.strtolower($complaint->status) }}">{{ $complaint->status }}</span></td>
                                <td>{{ $complaint->created_at->format('d M Y') }}
                                    <div class="muted">Updated {{ $complaint->updated_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a class="button secondary" href="{{ route('complaints.index', array_merge(request()->query(), ['edit' => $complaint->id])) }}">Edit</a>
                                        <form method="POST" action="{{ route('complaints.destroy', $complaint) }}" onsubmit="return confirm('Delete this complaint?')">
                                            @csrf @method('DELETE')
                                            <button class="danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="empty" colspan="7">No complaints found. Add the first one using the form.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($complaints->hasPages())
                <nav class="pagination" aria-label="Complaint pages">
                    @if ($complaints->onFirstPage())<span class="button secondary" aria-disabled="true">← Previous</span>
                    @else
                    <a class="button secondary" href="{{ $complaints->previousPageUrl() }}">← Previous</a>
                    @endif
                    <span>Page {{ $complaints->currentPage() }} of {{ $complaints->lastPage() }}</span>
                    @if ($complaints->hasMorePages())
                    <a class="button secondary" href="{{ $complaints->nextPageUrl() }}">Next →</a>
                    @else
                    <span class="button secondary" aria-disabled="true">Next →</span>
                    @endif
                </nav>
                @endif
            </section>
        </div>
    </main>
</body>

</html>