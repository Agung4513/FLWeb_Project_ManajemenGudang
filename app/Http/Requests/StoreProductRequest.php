<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'manager']);
    }

    protected $appends = ['formatted_buy_price', 'formatted_sell_price', 'is_low_stock'];

    public function getFormattedBuyPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->buy_price, 0, ',', '.');
    }

    public function getFormattedSellPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'buy_price'     => 'required|numeric|min:1',
            'sell_price'    => 'required|numeric|min:1|gte:buy_price',
            'current_stock' => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:1|lte:current_stock',
            'unit'          => 'required|in:pcs,box,kg,liter,botol,sachet',
            'location'      => 'nullable|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description'   => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'sell_price.gte'           => 'Harga jual tidak boleh lebih kecil dari harga beli!',
            'min_stock.lte'            => 'Stok minimum tidak boleh lebih besar dari stok awal!',
            'buy_price.min'            => 'Harga beli minimal Rp 1!',
            'sell_price.min'           => 'Harga jual minimal Rp 1!',
            'min_stock.min'            => 'Stok minimum minimal 1!',
        ];
    }
}
