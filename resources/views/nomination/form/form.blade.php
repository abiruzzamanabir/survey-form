<form action="{{ route('form.store') }}" method="POST" class="was-validated" novalidate>
    @csrf
    <div class="border p-3 shadow my-3">
        <h2 class="text-center">
            Bangladesh Brand Forum – Brand Academy Industry Competency Assessment Survey
        </h2>

        <h5>Purpose of the Survey</h5>
        <p>Bangladesh Brand Forum is undertaking an industry-wide assessment to identify the current state of marketing
            competencies in Bangladesh. The insights will help design the <b>Brand Academy</b> to uplift marketing
            capabilities across industries, ensuring Bangladeshi marketers are prepared for future challenges.
        </p>
        <p>We request
            your honest inputs to benchmark both the industry maturity and your organization’s competency maturity.</p>
    </div>
    {{-- PERSONAL DETAILS --}}
    <h5 class="text-center text-uppercase"><span class="text-decoration-underline">Section 1: Respondent
            Information</span></h5>
    <p class="text-center">(For classification only, not for evaluation)</p>

    <div class="border p-3 shadow my-3">
        @php
            $personalFields = [
                ['name' => 'name', 'label' => 'Full Name', 'placeholder' => 'Enter your full name', 'type' => 'text'],

                [
                    'name' => 'designation',
                    'label' => 'Designation',
                    'placeholder' => 'Your designation or job title',
                    'type' => 'text',
                ],
                [
                    'name' => 'organization',
                    'label' => 'Organization',
                    'placeholder' => 'Name of your organization',
                    'type' => 'text',
                ],
            ];
        @endphp

        @foreach ($personalFields as $field)
            <div class="mb-2">
                <label for="{{ $field['name'] }}" class="form-label"><b>{{ $field['label'] }} <span
                            class="text-danger">*</span></b></label>

                @if ($field['name'] === 'phone')
                    <input type="text" name="phone" id="phone" class="form-control"
                        placeholder="{{ $field['placeholder'] }}" value="{{ old('phone') }}" required
                        pattern="(\+8801|01)[0-9]{9}">
                    <div class="invalid-feedback">Please enter a valid Bangladeshi phone number.</div>
                @else
                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                        class="form-control" placeholder="{{ $field['placeholder'] }}" value="{{ old($field['name']) }}"
                        required>
                    <div class="invalid-feedback">Please enter your {{ strtolower($field['label']) }}.</div>
                @endif
            </div>
            <hr>
        @endforeach
        <div>
            @php
                $categories = [
                    'FMCG',
                    'Telecom',
                    'Banking & Financial Services',
                    'Retail',
                    'Technology & Digital',
                    'Others',
                ];
            @endphp

            <div class="mb-2">
                <label class="form-label"><b>Select Your Industry Sector <span class="text-danger">*</span></b></label>
                <select name="category" id="category" class="form-select" required>
                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>-- Select Industry
                        Sector --
                    </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select an industry sector.</div>
            </div>
            <hr>
            {{-- Other category input, hidden by default --}}
            <div class="mb-2" id="other-category-wrapper" style="display: none;">
                <label for="other_category" class="form-label"><b>Please specify other industry sector <span
                            class="text-danger">*</span></b></label>
                <input type="text" name="other_category" id="other_category" class="form-control"
                    placeholder="Specify other industry sector" value="{{ old('other_category') }}">
                <div class="invalid-feedback">Please specify the other industry sector.</div>
            </div>
        </div>

    </div>

    {{-- COMPETENCY ASSESSMENT --}}
    <h5 class="text-center text-uppercase mt-4"><span class="text-decoration-underline">Section 2: Competency Assessment
            – Industry vs Organization</span>
    </h5>

    <p class="text-center">For each competency, please distribute 100% across the four maturity levels (Basic / Working
        / Advanced / Expert).</p>

    <ul>
        <li><b>Basic</b> – Competency is largely absent in practice.</li>
        <li><b>Working</b> – Competency is emerging but not consistent.</li>
        <li><b>Advanced</b> – Competency is embedded in most practices.</li>
        <li><b>Expert</b> – Competency is mastered, driving significant advantage.</li>
    </ul>

    <div class="border p-3 shadow my-3">
        @php
            $competencies = [
                [
                    'name' => 'Collaborate to Win: Growth Levers',
                    'definition' => 'Collaborates across functions and nurtures long-term partnerships to drive growth',
                ],
                [
                    'name' => 'Being Analytical',
                    'definition' => 'Translates analytics into competitive advantage and enables decision-making.',
                ],
                [
                    'name' => 'Brand Purpose & Authenticity',
                    'definition' => 'Builds brand strategies rooted in authenticity, aligned with consumer values.',
                ],
                [
                    'name' => 'Digital & Data Fluency',
                    'definition' =>
                        'Uses digital channels and data-driven insights for brand building and consumer engagement.',
                ],
                [
                    'name' => 'Consumer-Centric Innovation',
                    'definition' => 'Innovates products, services, and experiences based on evolving consumer needs.',
                ],
                [
                    'name' => 'Marketing Communication Excellence',
                    'definition' => 'Crafts compelling brand narratives across traditional and digital touchpoints.',
                ],
                [
                    'name' => 'Sustainability & Responsibility',
                    'definition' => 'Embeds sustainability and social responsibility into brand strategy.',
                ],
                [
                    'name' => 'Brand–Agency Partnership Excellence',
                    'definition' =>
                        'Evaluates the current maturity of partnerships between Brands and Marketing/Creative Agencies in Bangladesh.',
                ],
                [
                    'name' => 'Leadership & Talent Development',
                    'definition' => 'Builds and nurtures marketing talent and future-ready teams.',
                ],
            ];

            $levels = ['basic', 'working', 'advanced', 'expert'];
        @endphp

        @foreach ($competencies as $index => $competency)
            <div class="mb-4">
                <h6><b>{{ $index + 1 }}. {{ $competency['name'] }}</b></h6>
                <p><i>{{ $competency['definition'] }}</i></p>

                <input type="hidden" name="competencies[{{ $index }}][name]"
                    value="{{ old("competencies.$index.name", $competency['name']) }}">
                <input type="hidden" name="competencies[{{ $index }}][definition]"
                    value="{{ old("competencies.$index.definition", $competency['definition']) }}">

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Industry Assessment (%)</h6>
                        @foreach ($levels as $level)
                            <div class="mb-2">
                                <label class="form-label">{{ ucfirst($level) }}:</label>
                                <input type="number" class="form-control"
                                    name="competencies[{{ $index }}][industry][{{ $level }}]"
                                    value="{{ old("competencies.$index.industry.$level") }}" min="0"
                                    max="100" required placeholder="e.g. 25%">
                                <div class="invalid-feedback">Enter {{ ucfirst($level) }} % (0-100).</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-success">My Organization/Team Assessment (%)</h6>
                        @foreach ($levels as $level)
                            <div class="mb-2">
                                <label class="form-label">{{ ucfirst($level) }}:</label>
                                <input type="number" class="form-control"
                                    name="competencies[{{ $index }}][organization][{{ $level }}]"
                                    value="{{ old("competencies.$index.organization.$level") }}" min="0"
                                    max="100" required placeholder="e.g. 25%">
                                <div class="invalid-feedback">Enter {{ ucfirst($level) }} % (0-100).</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
    {{-- SECTION 3: Priority & Gaps --}}
    <h5 class="text-center text-uppercase mt-4">
        <span class="text-decoration-underline">Section 3: Priority & Gaps</span>
    </h5>

    <div class="border p-3 shadow my-3">
        <p class="text-center">Which 3 competencies/trainings do you believe are most critical for the future of
            marketing in Bangladesh?</p>

        @for ($i = 1; $i <= 3; $i++)
            <div class="mb-3">
                <input type="text" name="priority_gaps[]" class="form-control"
                    placeholder="Enter critical competency/training #{{ $i }}"
                    value="{{ old("priority_gaps.$i") }}" required>
            </div>
        @endfor
    </div>

    {{-- SECTION 4: Final Thoughts --}}
    <h5 class="text-center text-uppercase mt-4">
        <span class="text-decoration-underline">Section 4: Final Thoughts</span>
    </h5>

    <div class="border p-3 shadow my-3">
        <p class="text-center">What single change do you think can transform the marketing profession in Bangladesh?</p>

        <div class="mb-3">
            <textarea name="final_thoughts" class="form-control" rows="4" placeholder="Share your final thoughts..."
                required>{{ old('final_thoughts') }}</textarea>
        </div>
    </div>


    {{-- SUBMIT BUTTON --}}
    <div class="border p-3 shadow my-3 text-center">
        <button type="submit" class="btn btn-primary px-4">Submit</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const otherCategoryWrapper = document.getElementById('other-category-wrapper');
        const otherCategoryInput = document.getElementById('other_category');

        function toggleOtherCategory() {
            if (categorySelect.value === 'Others') {
                otherCategoryWrapper.style.display = 'block';
                otherCategoryInput.setAttribute('required', 'required');
            } else {
                otherCategoryWrapper.style.display = 'none';
                otherCategoryInput.removeAttribute('required');
                otherCategoryInput.value = '';
            }
        }

        // Initial call for old value
        toggleOtherCategory();

        categorySelect.addEventListener('change', toggleOtherCategory);
    });
</script>
