<a href="{{ route('contacts.edit', $row->id) }}" title="Edit Contact">
    <i class="fa fa-edit" style="font-size:24px"></i>
</a>
<a href="javascript:void(0)" class="delete-contact ml-2" data-id="{{ $row->id }}" title="Delete Contact">
    <i class="fa fa-trash" style="font-size:24px; color:rgb(214, 0, 0)"></i>
</a>