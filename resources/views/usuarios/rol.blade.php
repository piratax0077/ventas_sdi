<div class="form-group">
    <label for="role">Rol</label>
    <select class="form-control" id="role" name="role" required>
        @foreach ([\App\Models\User::SELLER_ROLE, \App\Models\User::SUBADMIN_ROLE] as $rol)
            <option value="{{ $rol }}" @selected(old('role', $rolActual ?? \App\Models\User::SELLER_ROLE) === $rol)>
                {{ $rol }}
            </option>
        @endforeach
    </select>
    @error('role')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
