@extends('layouts.master-cmi', ['ClientID'=>$ClientID])

@title('Finalized Claims')

@page_title('Finalized Claims')

@body_class('client-profiles layout-box')

{{-- Let Document know the css block we're trying to add --}}
@css("/plugins/datepicker/datepicker3.css", "datepicker", 'app')

@js("/plugins/datepicker/bootstrap-datepicker.js", "datepicker", "app")
@jsblock('Claims.js.finalized-claims', 'js')
@section('body')
  <style>
    #page-title .toolbars {margin: 20px 0;}
      #page-title .toolbars a{
        padding: 20px;
        color: #fff;
        margin: 0;
      }
      #page-title .toolbars a:hover {
        color: #fff;
          background-color: #2579a9;
          border-color: #1f648b;
      }
    #page-title .title h5{margin: 0;}
    #search-fields td{position: relative;padding: 5px 0;}
    #search-fields td .btn-group{padding: 0;}
    #search-fields td input{text-align: left;}
    #search-fields .btn.trigger{
      position: absolute;
      right: 0;
      z-index: 100;
    }
    .profiles-list {
      display: block;
        overflow-x: scroll;
    }
    .profiles-list th, .profiles-list td {
      white-space:nowrap;
      line-height: 24px;
      vertical-align: middle !important;
    }
    .profiles-list td {
      height: 33px;
    }
    .profiles-list th {
        height: 50px;
    }
    .profiles-list thead {
      background: #006697;
      color: #fff;
    }
    /*.profiles-list tbody tr:hover {
      cursor: pointer;
    }*/
    .profiles-list tbody td a {
      text-decoration: underline;
    }
  </style>
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

  <div class = "main-content">

    <div class = "form-fields">
        <p id = "ReferenceNumber">ReferenceNumber: <span></span></p>
        <p id = "PolicyNumber">PolicyNumber: <span></span></p>
        <p id = "ClaimNumber">ClaimNumber: <span></span></p>
        <p id = "InvoiceNumber">InvoiceNumber: <span></span></p>
        <p id = "InsuredName">InsuredName: <span></span></p>
        <p id = "ContactName">ContactName: <span></span></p>
        <p id = "Address">Address: <span></span></p>
        <p id = "Classification">Classification: <span></span></p>
        <p id = "Underwriter">Underwriter: <span></span></p>
        <p id = "Product">Product: <span></span></p>
        <p id = "ContactName">ContactName: <span></span></p>
        <hr>
        <p id = "Adviser/Consultant">Adviser/Consultant: <span></span></p>
        <p id = "Status">Status: <span></span></p>
        <p id = "LodgedBy">LodgedBy: <span></span></p>
        <p id = "TypeOfClaim">TypeOfClaim: <span></span></p>
        <p id = "DateLodged">DateLodged: <span></span></p>

      
    </div>

    <table id="tbl-finalized-claims" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Reference Number</th>
                <th>Date lodged</th>
                <th>Policy Number</th>
                <th>Insured Name</th>
                <th>Claim Number</th>
                <th>Underwriter</th>
                <th>Product</th>
                <th>Excess </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Reference Number</th>
                <th>Date lodged</th>
                <th>Policy Number</th>
                <th>Insured Name</th>
                <th>Claim Number</th>
                <th>Underwriter</th>
                <th>Product</th>
                <th>Excess </th>
            </tr>
        </tfoot>
    </table>
  </div>
@endsection
  