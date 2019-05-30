                 <form id="insuranceQuote-update" method="POST">               
                <div class="row">
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-Underwriter">Underwriter</label>
                            <select class="form-control" id="QuoteDetails-Quote-Underwriter" name="UnderwriterID">';
                                @foreach ($Quote['Underwriter'] as $value)
                                    @php $stat = '';
                                    if ($value->UnderwriterID == $Quote['UnderwriterID'])
                                            $stat = 'selected';
                                    @endphp
                                    <option value="{{ $value->UnderwriterID }}" {{ $stat }}>{{ $value->CompanyName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-Classification">Classification</label>
                            <input class="form-control" id="QuoteDetails-Quote-Classification" name="Classification" type="text" value="{{ $Quote['Classification'] }}">
                        </div>
                    </div> 

                </div>

                <div class="row">
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-StartDate">CoverStartDateTime</label>
                            <input class="form-control" id="QuoteDetails-Quote-StartDate" name="StartDate" type="text" value="{{ $Quote['CoverStartDateTime'] }}">
                        </div>
                    </div>
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-EndDate">CoverEndtDateTime</label>
                            <input class="form-control datetimepicker" id="QuoteDetails-Quote-EndDate" name="EndDate" type="text" value="{{ $Quote['CoverEndDateTime'] }}">
                        </div>
                    </div>         
                </div>

                <div class="row">
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-EffectiveDate">EffectiveDateTime</label>
                            <input class="form-control" id="QuoteDetails-Quote-EffectiveDate" name="EffectiveDate" type="text" value="{{ $Quote['EffectiveDateTime'] }}">
                        </div>
                    </div> 

                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-ExpiryDate">ExpiryDateTime</label>
                            <input class="form-control" id="QuoteDetails-Quote-ExpiryDate" name="ExpiryDate" type="text" value="{{ $Quote['ExpiryDateTime'] }}">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-FinalizedDate">FinalizedDateTime</label>
                            <input class="form-control" id="QuoteDetails-Quote-FinalizedDate" name="FinalizedDate" type="text" value="{{ $Quote['FinalizedDateTime'] }}">
                        </div>
                    </div> 

                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-Product">Product</label>
                            <input class="form-control" id="QuoteDetails-Quote-Product" name="Product" type="text" value="{{ $Quote['Product'] }}">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-Premium">Premium</label>
                            <input class="form-control" id="QuoteDetails-Quote-Premium" name="Premium" type="text" value="{{ $Quote['Premium'] }}">
                        </div>
                    </div> 

                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-Excess">Excess</label>
                            <input class="form-control" id="QuoteDetails-Quote-Excess" name="Excess" type="text" value="{{ $Quote['Excess'] }}">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-ImposedExcess">ImposedExcess</label>
                            <input class="form-control" id="QuoteDetails-Quote-ImposedExcess" name="ImposedExcess" type="text" value="{{ $Quote['ImposedExcess'] }}">
                        </div>
                    </div> 

                    <div class="col-md-6 required">
                        <div class="form-group">
                            <label for="QuoteDetails-Quote-PolicyType">Policy Type</label>
                            <input class="form-control" id="QuoteDetails-Quote-PolicyType" name="PolicyType" type="text">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" class="form-control" id="QuoteDetails-Quote-currentUserId" name="currentUserId" value="{{ $Quote['currentUserId'] }}"> 
                        <input type="hidden" class="form-control" id="QuoteDetails-Quote-InsuranceQuoteID" name="InsuranceQuoteID" value="{{ $QuoteID }}">
                        <input type="hidden" class="form-control" id="QuoteDetails-Quote-RFQID" name="RFQID" value="{{ $Quote['RFQ']['RFQID'] }}">
                        <input type="hidden" class="form-control" id="QuoteDetails-Quote-AddressID" name="AddressID" value="{{ $Quote['AddressID'] }}">
                        <input type="hidden" class="form-control" id="QuoteDetails-Quote-PolicyTypeID" name="PolicyTypeID" value="{{ $Quote['PolicyTypeID'] }}">
                        <input type="hidden" class="form-control" id="QuoteDetails-Quote-currentUserId" name="currentUserId" value="{{ $Quote['currentUserId'] }}">

                        <input type="submit" class="btn btn-default btn-nolft-margin" id="insuranceQuote-btn-update" value="Update">
                    </div>
                </div>

            </form>