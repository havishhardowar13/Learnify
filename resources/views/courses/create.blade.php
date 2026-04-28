@extends('layouts.app')
@section('title', isset($course) ? 'Edit Course' : 'Create Course')

@section('content')
<section class="section" style="max-width:720px;margin:0 auto">
    <div class="section-header" style="text-align:left">
        <h2>{{ isset($course) ? 'Edit Course' : 'Create New Course' }}</h2>
        <hr class="divider" style="margin-left:0">
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin:0;padding-left:1.2rem">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        @if(isset($course))
            <form method="POST" action="{{ route('courses.update', $course) }}">
            @method('PUT')
        @else
            <form method="POST" action="{{ route('courses.store') }}">
        @endif
        @csrf

            <div class="form-group">
                <label for="title">Course Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" class="form-control @error('title') is-error @enderror"
                    value="{{ old('title', $course->title ?? '') }}" required minlength="5">
                @error('title')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description">Description <span class="required">*</span></label>
                <textarea id="description" name="description" rows="5"
                    class="form-control @error('description') is-error @enderror"
                    required minlength="20">{{ old('description', $course->description ?? '') }}</textarea>
                @error('description')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="price">Price ($) <span class="required">*</span></label>
                    <input type="number" id="price" name="price" step="0.01" min="0"
                        class="form-control @error('price') is-error @enderror"
                        value="{{ old('price', $course->price ?? '') }}" required>
                    @error('price')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="original_price">Original Price ($)</label>
                    <input type="number" id="original_price" name="original_price" step="0.01" min="0"
                        class="form-control"
                        value="{{ old('original_price', $course->original_price ?? '') }}">
                    <span class="field-hint">Optional — shows a "sale" crossed-out price</span>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="category">Category <span class="required">*</span></label>
                    <select id="category" name="category"
                        class="form-control @error('category') is-error @enderror" required>
                        <option value="">Select category…</option>
                        @foreach(['Web Development','Design','Data Science','Marketing','Cybersecurity','Business','Other'] as $cat)
                            <option value="{{ $cat }}"
                                {{ old('category', $course->category ?? '') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="duration">Duration</label>
                    <input type="text" id="duration" name="duration" placeholder="e.g. 12 hours"
                        class="form-control"
                        value="{{ old('duration', $course->duration ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="hidden"   name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1"
                        {{ old('is_published', $course->is_published ?? false) ? 'checked' : '' }}>
                    <span>Publish this course immediately</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ isset($course) ? 'Save Changes' : 'Create Course' }}
                </button>
                <a href="{{ route('dashboard.instructor') }}" class="btn btn-outline">Cancel</a>

                @isset($course)
                    <form method="POST" action="{{ route('courses.destroy', $course) }}"
                          style="margin-left:auto"
                          onsubmit="return confirm('Delete this course? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                @endisset
            </div>

        </form>
    </div>
</section>
@endsection
