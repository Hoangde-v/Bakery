@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Vui lòng kiểm tra lại thông tin:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="category_id" class="form-label font-weight-bold">Danh mục <span class="text-danger">*</span></label>
    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
    <input id="name" type="text" name="name" maxlength="150" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $product->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label font-weight-bold">Mô tả</label>
    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
              rows="4">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="price" class="form-label font-weight-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
        <input id="price" type="number" name="price" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', $product->price ?? '') }}" required>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="stock_quantity" class="form-label font-weight-bold">Số lượng tồn kho <span class="text-danger">*</span></label>
        <input id="stock_quantity" type="number" name="stock_quantity" min="0" class="form-control @error('stock_quantity') is-invalid @enderror"
               value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
        @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="status" class="form-label font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="available"
            @selected(old('status', $product->status ?? 'available') === 'available')>
            Đang bán
        </option>
        <option value="unavailable"
            @selected(old('status', $product->status ?? '') === 'unavailable')>
            Ngừng bán
        </option>
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label font-weight-bold">Ảnh sản phẩm</label>
    <input id="image" type="file" name="image" class="form-control @error('image') is-invalid @enderror"
           accept="image/jpeg,image/png,image/webp">
    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <div class="form-text">Định dạng JPG, PNG hoặc WEBP; dung lượng tối đa 2 MB.</div>
    
    @isset($product)
        @if ($product->image)
            <div class="mt-2">
                <img src="{{ Storage::url($product->image) }}"
                     alt="{{ $product->name }}" class="img-thumbnail preview-image current-product-image">
            </div>
        @endif
    @endisset
    <img id="image-preview" class="img-thumbnail preview-image mt-2 d-none" alt="Xem trước ảnh sản phẩm mới">

    @push('scripts')
    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            imagePreview.src = URL.createObjectURL(file);
            imagePreview.classList.remove('d-none');
            document.querySelector('.current-product-image')?.classList.add('d-none');
        });
    </script>
    @endpush
</div>