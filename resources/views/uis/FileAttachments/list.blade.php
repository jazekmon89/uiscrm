@if(isset($attachments) && count($attachments))
  @foreach($attachments as $attachment)
    <tr>
      <td><a href="{{ route('attachments.download',[$attachment->FileAttachmentID]) }}" target="_blank">{{ $attachment->FileName }}</a></td>
      <td>{{ $attachment->Title }}</td>
      <td>{{ $attachment->Comments }}</td>
      <td>{{ $attachment->DocumentType }}</td>
      <td>{{ $attachment->CreatedDateTime }}</td>
      <td>{{ $attachment->CreatedBy }}</td>
      @if($can_update)
      <td>
        <a class='attachment-edit' data-aid="{{ $attachment->FileAttachmentID }}" data-all="{{ json_encode($attachment) }}" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
        <a class='attachment-delete' data-aid="{{ $attachment->FileAttachmentID }}" title="Delete"><span class="glyphicon glyphicon-remove-circle"></span></a>
      </td>
      @endif
    </tr>
  @endforeach
@else
  <tr><td colspan='{{ $can_update?7:6 }}' style='text-align:center'>No data.</td></tr>
@endif