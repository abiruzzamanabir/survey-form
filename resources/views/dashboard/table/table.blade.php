<div class="table-responsive">
    <table id="dashboard" class="table table-striped table-bordered table-hover text-center">
        <thead class="table-info sticky-top">
            <tr>
                <th>#</th>
                <th>Submitted On</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Organization</th>
                <th>Category</th>
                <th>Competencies</th>
                <th>Priority & Gaps</th> <!-- ✅ NEW -->
                <th>Final Thoughts</th> <!-- ✅ NEW -->
            </tr>
        </thead>
        <tbody>
            @foreach ($all_nomination as $item)
                <tr class="{{ !empty($item->comment) ? 'has-comment' : '' }}">
                    <th scope="row" style="cursor:pointer;" onclick="copyUserId('{{ $item->ukey }}')"
                        title="Copy user key">
                        {{ $loop->iteration }}
                    </th>
                    <td>{{ $item->created_at->format('l, F j, Y, g:i A') }}</td>
                    <td class="text-capitalize">{{ $item->name }}</td>
                    <td>{{ $item->designation }}</td>
                    <td>{{ $item->organization }}</td>
                    <td>{{ $item->category }}</td>

                    {{-- ✅ Competencies Column --}}
                    <td style="min-width: 250px; text-align: left;">
                        @php
                            $competencies = json_decode($item->competencies, true);
                        @endphp
                        @if (!empty($competencies))
                            <ul class="list-unstyled mb-0">
                                @foreach ($competencies as $competency)
                                    <li class="mb-2">
                                        <strong>{{ $competency['name'] ?? 'N/A' }}</strong><br>
                                        <small class="text-muted d-block">
                                            <u>Industry:</u>
                                            Basic: {{ $competency['industry']['basic'] ?? '0' }}% |
                                            Working: {{ $competency['industry']['working'] ?? '0' }}% |
                                            Advanced: {{ $competency['industry']['advanced'] ?? '0' }}% |
                                            Expert: {{ $competency['industry']['expert'] ?? '0' }}%
                                        </small>
                                        <small class="text-muted d-block">
                                            <u>Organization:</u>
                                            Basic: {{ $competency['organization']['basic'] ?? '0' }}% |
                                            Working: {{ $competency['organization']['working'] ?? '0' }}% |
                                            Advanced: {{ $competency['organization']['advanced'] ?? '0' }}% |
                                            Expert: {{ $competency['organization']['expert'] ?? '0' }}%
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No Competencies</span>
                        @endif
                    </td>
                    {{-- ✅ Priority & Gaps Column --}}
                    <td style="min-width: 200px; text-align: left;">
                        @php
                            $gaps = json_decode($item->priority_gaps, true);
                        @endphp
                        @if (!empty($gaps) && is_array($gaps))
                            <ul class="mb-0 ps-3">
                                @foreach ($gaps as $gap)
                                    <li>{{ $gap }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>

                    {{-- ✅ Final Thoughts Column --}}
                    <td style="min-width: 200px; text-align: left;">
                        @if (!empty($item->final_thoughts))
                            <small>{{ $item->final_thoughts }}</small>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
