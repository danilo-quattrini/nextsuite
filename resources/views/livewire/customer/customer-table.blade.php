<div>
    <x-table :customers="$customers" />
    <x-popup.delete-popup :show-delete-modal="$showDeleteModal"/>
    <x-popup.review-popup :show-review-modal="$showReviewModal" :rating="$rating"/>
</div>
