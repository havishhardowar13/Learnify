@extends('layouts.app')
@section('title', 'All Courses')

@push('styles')
<style>
  .ajax-spinner { display:none; text-align:center; padding:3rem; }
  .ajax-spinner i { font-size:2.5rem; color:var(--primary); }
  .schema-badge { display:inline-block; font-size:.7rem; padding:.15rem .5rem;
    border-radius:4px; margin-left:.4rem; font-weight:700; vertical-align:middle; }
  .schema-ok   { background:#d4edda; color:#155724; }
  .schema-fail { background:#f8d7da; color:#721c24; }
  #schema-status { font-size:.82rem; color:var(--text-muted); margin-top:.4rem; min-height:1.2em; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h2>All Courses</h2>
        <hr class="divider">
        <p>Results loaded live via <strong>jQuery AJAX + JSON Schema validation</strong></p>
    </div>

    <div class="filter-bar">
        <div class="search-wrap">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search-input" placeholder="Search courses..."
                   class="form-control search-input" autocomplete="off">
        </div>
        <select id="category-select" class="form-control category-select">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
        <button id="clear-btn" class="btn btn-outline" style="display:none">
            <i class="fas fa-times"></i> Clear
        </button>
        <div style="margin-left:auto; text-align:right;">
            <span id="schema-badge"></span>
            <div id="schema-status">Waiting for first load…</div>
        </div>
    </div>

    <div class="ajax-spinner" id="spinner">
        <i class="fas fa-circle-notch fa-spin"></i>
        <p style="color:var(--text-light);margin-top:.8rem">Loading courses…</p>
    </div>

    <div class="alert alert-error" id="ajax-error" style="display:none">
        <i class="fas fa-exclamation-circle"></i> <span id="error-message"></span>
    </div>

    <div class="courses-grid" id="courses-container"></div>

    <div id="pagination" class="pagination-wrap" style="display:none; gap:.8rem; justify-content:center; align-items:center; margin-top:2rem">
        <button id="prev-btn" class="btn btn-outline btn-sm"><i class="fas fa-chevron-left"></i> Prev</button>
        <span id="page-info" style="color:var(--text-light);font-size:.9rem"></span>
        <button id="next-btn" class="btn btn-outline btn-sm">Next <i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="empty-state" id="no-results" style="display:none">
        <i class="fas fa-search empty-icon"></i>
        <h3>No courses found</h3>
        <p>Try a different search term or category.</p>
        <button id="clear-btn-2" class="btn btn-primary">Clear Filters</button>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ajv/8.12.0/ajv2020.min.js"></script>
<script>
$(function () {
    let currentPage = 1, searchTerm = '', category = '';
    let ajv, validateCourse;

    // ── JSON Schema ───────────────────────────────────────────────────────
    const courseSchema = {
        $schema: "http://json-schema.org/draft-07/schema#",
        type: "object",
        required: ["title", "description", "price", "category"],
        properties: {
            id:             { type: "integer" },
            title:          { type: "string", minLength: 5, maxLength: 255 },
            description:    { type: "string", minLength: 20 },
            category:       { type: "string",
                              enum: ["Web Development","Design","Data Science",
                                     "Marketing","Cybersecurity","Business","Other"] },
            price:          { type: "number", minimum: 0 },
            original_price: { type: ["number","null"] },
            rating:         { type: "number", minimum: 0, maximum: 5 },
            reviews_count:  { type: "integer", minimum: 0 },
            is_published:   { type: "boolean" }
        }
    };

    try {
        ajv = new ajv2020();
        validateCourse = ajv.compile(courseSchema);
    } catch(e) { console.warn('Ajv init failed', e); }

    // ── Helpers ───────────────────────────────────────────────────────────
    function showSpinner()  { $('#spinner').show(); $('#courses-container,#pagination,#no-results,#ajax-error').hide(); }
    function hideSpinner()  { $('#spinner').hide(); }

    function stars(r) {
        let s = ''; r = r || 0;
        for (let i = 1; i <= 5; i++) s += `<i class="fas fa-star${i <= Math.round(r) ? '' : '-o'}"></i>`;
        return s;
    }

    function card(c) {
        const icons = {'Web Development':'fa-code','Design':'fa-palette','Data Science':'fa-chart-line','Marketing':'fa-bullseye','Cybersecurity':'fa-shield-alt'};
        const icon  = icons[c.category] || 'fa-book';
        const disc  = (c.original_price && c.original_price > c.price)
                    ? `<span class="original-price">$${parseFloat(c.original_price).toFixed(2)}</span>` : '';
        const base  = window.location.pathname.replace(/\/courses.*/, '');
        return `
        <div class="course-card fade-in-up">
            <div class="course-badge">${c.category || 'Course'}</div>
            <div class="course-image"><i class="fas ${icon}"></i></div>
            <div class="course-content">
                <span class="course-category">${c.category||''}</span>
                <h3 class="course-title">${c.title}</h3>
                <div class="course-meta">
                    <span class="rating">${stars(c.rating)}</span>
                    <span class="review-count">(${c.reviews_count||0} reviews)</span>
                </div>
                <p class="course-description">${(c.description||'').substring(0,110)}…</p>
                <div class="course-footer">
                    <div class="course-price">
                        <span class="price">$${parseFloat(c.price).toFixed(2)}</span>${disc}
                    </div>
                    <a href="${base}/courses/${c.id}" class="btn-enroll">View Course</a>
                </div>
            </div>
        </div>`;
    }

    // ── Schema validation display ─────────────────────────────────────────
    function runSchemaValidation(courses) {
        if (!validateCourse) { $('#schema-status').text('Ajv not loaded.'); return; }
        let ok = 0, fail = 0, firstErr = null;
        courses.forEach(c => { if (validateCourse(c)) ok++; else { fail++; if (!firstErr) firstErr = validateCourse.errors; } });
        if (fail === 0) {
            $('#schema-badge').html('<span class="schema-badge schema-ok"><i class="fas fa-check"></i> Schema Valid</span>');
            $('#schema-status').text(`All ${ok} courses passed JSON Schema validation.`);
        } else {
            $('#schema-badge').html('<span class="schema-badge schema-fail"><i class="fas fa-times"></i> Schema Error</span>');
            const msg = firstErr ? firstErr[0].instancePath + ' ' + firstErr[0].message : 'unknown';
            $('#schema-status').text(`${fail} course(s) failed. First error: ${msg}`);
        }
    }

    // ── AJAX fetch ────────────────────────────────────────────────────────
    function loadCourses(page) {
        showSpinner();
        const params = { page };
        if (searchTerm) params.search   = searchTerm;
        if (category)   params.category = category;

        $.ajax({
            url:      '/learnify-laravel/public/api/courses',
            method:   'GET',
            data:     params,
            dataType: 'json',
            headers:  { Accept: 'application/json' },
            success: function(res) {
                hideSpinner();
                const courses = res.data || [], meta = res.meta || {};
                if (!courses.length) { $('#no-results').show(); return; }
                $('#courses-container').html(courses.map(card).join('')).show();
                if (meta.last_page > 1) {
                    currentPage = meta.current_page;
                    $('#page-info').text(`Page ${meta.current_page} of ${meta.last_page} (${meta.total} total)`);
                    $('#prev-btn').prop('disabled', meta.current_page <= 1);
                    $('#next-btn').prop('disabled', meta.current_page >= meta.last_page);
                    $('#pagination').show();
                }
                runSchemaValidation(courses);
            },
            error: function(xhr) {
                hideSpinner();
                $('#error-message').text(xhr.responseJSON?.message || 'API request failed.');
                $('#ajax-error').show();
            }
        });
    }

    // ── Events ────────────────────────────────────────────────────────────
    let timer;
    $('#search-input').on('input', function() {
        clearTimeout(timer);
        searchTerm = $(this).val().trim();
        timer = setTimeout(() => { currentPage=1; toggleClear(); loadCourses(1); }, 300);
    });
    $('#category-select').on('change', function() { category=$(this).val(); currentPage=1; toggleClear(); loadCourses(1); });

    function clearAll() { searchTerm=''; category=''; currentPage=1; $('#search-input').val(''); $('#category-select').val(''); toggleClear(); loadCourses(1); }
    function toggleClear() { $('#clear-btn').toggle(!!(searchTerm||category)); }
    $('#clear-btn, #clear-btn-2').on('click', clearAll);
    $('#prev-btn').on('click', () => { if(currentPage>1) loadCourses(--currentPage); });
    $('#next-btn').on('click', () => loadCourses(++currentPage));

    loadCourses(1);
});
</script>
@endpush
