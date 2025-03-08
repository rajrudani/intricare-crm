@if ($row->merged_with)
    <b class="text-danger">Merged</b>
@else
    <a href="{{ route('contacts.edit', $row->id) }}" title="Edit Contact">
        <i class="fa fa-edit" style="font-size:20px"></i>
    </a>
    <a href="javascript:void(0)" class="delete-contact ml-2" data-id="{{ $row->id }}" title="Delete Contact">
        <i class="fa fa-trash" style="font-size:20px; color:rgb(214, 0, 0)"></i>
    </a>
    <a href="javascript:void(0)" class="merge-contact ml-2" data-id="{{ $row->id }}" title="Merge Contact">
        <i class="fa fa-random" style="font-size:20px; color:rgb(86, 0, 214)"></i>
    </a>
@endif
