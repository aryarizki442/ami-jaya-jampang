<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAddressController extends Controller
{
    private function user() { return JWTAuth::parseToken()->authenticate(); }


    public function index()
    {
        $addresses = $this->user()->addresses()->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $addresses,
        ]);
    }


    public function store(Request $request)
    {
        $user = $this->user();

        $validated = $request->validate([
            'label'          => 'required|in:home,office,other',
            'recipient_name' => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'province'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'village'        => 'nullable|string|max:100',
            'postal_code'    => 'nullable|string|max:10',
            'detail'         => 'nullable|string',
            'is_primary'     => 'boolean',
        ], [
            'label.required'          => 'Label alamat wajib diisi',
            'recipient_name.required' => 'Nama penerima wajib diisi',
            'phone.required'          => 'No. telepon wajib diisi',
            'province.required'       => 'Provinsi wajib diisi',
            'city.required'           => 'Kota wajib diisi',
            'district.required'       => 'Kecamatan wajib diisi',
        ]);

        // Jika set sebagai primary, unset yang lain
        if (! empty($validated['is_primary'])) {
            $user->addresses()->update(['is_primary' => 0]);
        }

        // Auto set primary jika ini alamat pertama
        if ($user->addresses()->count() === 0) {
            $validated['is_primary'] = 1;
        }

        $address = $user->addresses()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data'    => $address,
        ], 201);
    }

 
    public function show(UserAddress $address)
    {
        abort_if($address->user_id !== $this->user()->id, 403, 'Akses ditolak');

        return response()->json([
            'success' => true,
            'data'    => $address,
        ]);
    }

   
    public function update(Request $request, UserAddress $address)
    {
        $user = $this->user();
        abort_if($address->user_id !== $user->id, 403, 'Akses ditolak');

        $validated = $request->validate([
            'label'          => 'sometimes|in:home,office,other',
            'recipient_name' => 'sometimes|string|max:100',
            'phone'          => 'sometimes|string|max:20',
            'province'       => 'sometimes|string|max:100',
            'city'           => 'sometimes|string|max:100',
            'district'       => 'sometimes|string|max:100',
            'village'        => 'nullable|string|max:100',
            'postal_code'    => 'nullable|string|max:10',
            'detail'         => 'nullable|string',
            'is_primary'     => 'boolean',
        ]);

        if (! empty($validated['is_primary'])) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_primary' => 0]);
        }

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui',
            'data'    => $address->fresh(),
        ]);
    }

    
    public function destroy(UserAddress $address)
    {
        $user = $this->user();
        abort_if($address->user_id !== $user->id, 403, 'Akses ditolak');

        // Jika hapus primary, set primary ke alamat lain
        if ($address->is_primary) {
            $other = $user->addresses()->where('id', '!=', $address->id)->first();
            $other?->update(['is_primary' => 1]);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus',
        ]);
    }


    public function setPrimary(UserAddress $address)
    {
        $user = $this->user();
        abort_if($address->user_id !== $user->id, 403, 'Akses ditolak');

        $user->addresses()->update(['is_primary' => 0]);
        $address->update(['is_primary' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat utama berhasil diubah',
            'data'    => $address->fresh(),
        ]);
    }
}