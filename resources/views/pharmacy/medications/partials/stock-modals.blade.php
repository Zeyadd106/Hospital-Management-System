<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addStockForm" action="{{ route('pharmacy.medications.stock.add', $medication) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quantityToAdd" class="form-label">Quantity to Add</label>
                        <input type="number" class="form-control" id="quantityToAdd" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="batchNumber" class="form-label">Batch Number</label>
                        <input type="text" class="form-control" id="batchNumber" name="batch_number" value="{{ $medication->batch_number ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiryDate" name="expiry_date" 
                               value="{{ $medication->expiry_date ? $medication->expiry_date->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchasePrice" class="form-label">Purchase Price (per unit)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="purchasePrice" name="purchase_price" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="sellingPrice" class="form-label">Selling Price (per unit)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="sellingPrice" name="selling_price" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-labelledby="adjustStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="adjustStockForm" action="{{ route('pharmacy.medications.stock.adjust', $medication) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="adjustStockModalLabel">Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adjustmentType" class="form-label">Adjustment Type</label>
                        <select class="form-select" id="adjustmentType" name="type" required>
                            <option value="add">Add to Stock</option>
                            <option value="remove">Remove from Stock</option>
                            <option value="set">Set Exact Amount</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="batchNumberAdjust" class="form-label">Batch Number</label>
                        <input type="text" class="form-control" id="batchNumberAdjust" name="batch_number" value="{{ $medication->batch_number ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="expiryDateAdjust" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiryDateAdjust" name="expiry_date" 
                               value="{{ $medication->expiry_date ? $medication->expiry_date->format('Y-m-d') : '' }}" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Adjustment</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        <div class="form-text">Please provide a reason for this adjustment (e.g., damaged, expired, received new stock)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
