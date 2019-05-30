<style>
	.sidebar-rfqs{
		background: #006697;
		color: white;
		height: 100%;
		float: left;
	}

	.sidebar-rfqs a{
		text-decoration: none;	
	}

</style>

<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li>
            <a href = "{{ route('rfqs.edit', $RFQID) }}">
                RFQ 
            </a>
        </li>
        <li>
            <a href = "{{ route('rfqs.tasks', $RFQID) }}">
                Tasks
            </a>
        </li>
        <li>
            <a href = "{{ route('rfqs.notes', $RFQID) }}">
                Notes
            </a>
        </li>
        <li>
            <a href = "{{ route('rfqs.attachments', $RFQID) }}">
                Attachments
            </a>
        </li>
    </ul>
</div>