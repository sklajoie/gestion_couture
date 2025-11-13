@extends('layouts.master')
@section('content')

 <!-- Content Wrapper. Contains page content -->
 {{-- <div class="content-wrapper "> --}}
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>VENTES</h1>
             @if ($message = Session::get('success'))
                        <div class="alert alert-success  alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>  
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
            @if ($message = Session::get('danger'))
                        <div class="alert alert-danger  alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>  
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">×</button>  
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('filament.admin.resources.ventes.index')}}">LISTE VENTE</a></li>
              <li class="breadcrumb-item active">VENTES</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
    <div class="row">
        
        {{-- <div class="col-12"> --}}
                    <!-- debut service -->
            <div class="col-md-6" style="overflow-y:auto;height:585px;">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="rechercheproduit" placeholder="rechercher produit" required
                            class="form-control"><br>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="recherchedesis" placeholder="rechercher service" required
                            class="form-control"><br>
                    </div>


                    <div class="col-md-12">
                        <h1>Services</h1>
                        <hr>
                        <div class="col-md-12 row" id="service"></div>
                    </div>

                    <div class="col-md-12">
                        <h1>Produits</h1>
                        <hr>
                        <div class="col-md-12 row" id="produit"></div>
                    </div>

                </div>
            </div>

                <!-- /.card -->
            
                <!-- debut commande -->
            <div class="col-md-6" style="overflow-y:auto;height:585px;">
                <div class="row">
                <div class="col-md-6">  
               <p>Imprimer la facture derniere OP </p>
            </div>
            <div class="col-md-3">  
              <a title="Imprimer vente"  style="background:#00688f;color:#fff;" class="btn " href="{{route('derniere-vente-imprimer')}}" target="blank">Facture A4</a>
            </div>
            <div class="col-md-3">  
              <a title="Imprimer vente"  style="background:#00688f;color:#fff;" class="btn " href="{{route('derniere-vente-ticket-imprimer')}}" target="blank">Facture B5</a>
            </div>
            </div>
                <div class="row">
                <form role="form" method="POST"  action="/save-vente" enctype="multipart/form-data">
                
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-6">
                        <label for="checkin">Client</label>
                        <input type="text" id="nomclient" name="nomclient" required class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="checkin">Contact</label>
                        <input type="text" id="numero" name="numeroclient" class="form-control"><br>
                    </div>
                    </div>

                    <div class="col-md-12">
                        <table border="1" cellpadding="1" cellspacing="1" class="table table-responsive" style="width:100%;">
                            <thead>
                                <tr">
                                    <th class=" text-center" style="font-size:16px;width:40%;">Désignation</th>
                                    <th class="text-center" style="font-size:16px;width:15%;">Prix</th>
                                    <th class="text-center" style="font-size:16px;width:10%;">Stock</th>
                                    <th class="text-center" style="font-size:16px;width:10%;">Qte</th>
                                    <th class="text-center" style="font-size:16px;width:20%;">Total</th>
                                    <th class="text-center" style="font-size:16px;width:5%;"></th>
                                </tr>

                            </thead>
                            <tbody class="contenu">


                            </tbody>
                        </table>
                        <hr>
                        <div class="row">

                            <div class="col-md-4">
                                <label for="checkin">Total HT</label>
                                <input type="text" id="vue_totalht"  readonly class="form-control">
                                <input type="hidden" id="totalht" name="totalht">
                            </div>
                            <div class="col-md-4">
                                <label for="checkin">Remise</label>
                                <input type="text" id="remise" name="remise" onkeyup="remises()" onclick="remises()" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="checkin">TVA</label>
                                <select type="text" id="tva" name="tva" class="form-control taxetva">
                                    <option value="0.0">0%</option>
                                    <option value="0.18">18%</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="checkin">Total TTC</label>
                                <input type="text" id="vue_totalttc" readonly class="form-control">
                                <input type="hidden" id="totalttc" name="totalttc">
                            </div>

                            <div class="col-md-4">
                                <label for="checkin">Montant versé</label>
                                <input type="text" id="avance" name="avance" onkeyup="avances()" onclick="avances()" required
                                    class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="checkin">Solde</label>
                                <input type="text" id="vue_solde" readonly class="form-control">
                                <input type="hidden" id="solde" name="solde">
                            </div>
                           
                             <div class="col-md-4" style="margin-bottom: 10px">
                                <label for="checkin">Agence</label>
                                   <select required type="text" id="agence" name="agence" class="form-control">
                                    <option value="">Choix Agence</option>
                                    @foreach ($agences as $agence )
                                    <option value="{{$agence->id}}">{{$agence->nom}}</option>
                                    @endforeach
                                   </select>
                            </div>
                             <div class="col-md-4" style="margin-bottom: 10px">
                                <label for="checkin">Caisse</label>
                                   <select required type="text" id="caisse" name="caisse" class="form-control">
                                    <option value="">Choix Caisse</option>
                                    @foreach ($caisse as $caiss )
                                    <option value="{{$caiss->id}}">{{$caiss->nom}}</option>
                                    @endforeach
                                   </select>
                            </div>
                             <div class="col-md-4" style="margin-bottom: 10px">
                                <label for="checkin">Moyen Paiement</label>
                                   <select required type="text" id="paiement" name="paiement" class="form-control">
                                    <option value="Especes">Espèces</option>
                                    <option value="Mobile Money">Mobile Money</option>
                                    <option value="Wave">Wave</option>
                                    <option value="Virement Bancaire">Virement Bancaire</option>
                                    <option value="Cheque">Chèque</option>
                                    <option value="Autre">Autre</option>
                                    <option value="Recouvrement">Recouvrement</option>
                                  
                                   </select>
                            </div>
                             <div class="col-md-4" style="margin-bottom: 10px">
                                <label for="checkin">Moyen Paiement</label>
                                 <textarea class="form-control" name="detail" id="" cols="20" rows="2"></textarea>
                            </div>
                            <br>
                            <div class="col-md-12">
                                {{-- <table style="width:100%;">
                                    <tr>

                                        <td style="background-color:#00688f;color:#fff;width:25%;"><label
                                                class="checkbox-inline on"><input type="radio" id="cash" onclick="cash()" name="paiement"
                                                    value="Especes">ESPECE</label></td>
                                        <td style="background-color:#00688f;color:#fff;width:25%;"><label
                                                class="checkbox-inline on"><input type="radio" onclick="cheque()" class="cheque" id="cheque" name="paiement"
                                                    value="CHEQUE">CH&Egrave;QUE</label></td>
                                        <td style="background-color:#00688f;color:#fff;width:25%;"><label
                                                class="checkbox-inline on"><input type="radio" onclick="carte()" id="carte" name="paiement"
                                                    value="CARTE/MOBILE MONEY">CARTE / MOBILE MONEY</label></td>
                                        <td style="background-color:#00688f;color:#fff;width:25%;"><label
                                                class="checkbox-inline off"><input type="radio" name="paiement" onclick="carte()" id="carte"
                                                    value="Wave">WAVE</label> </td>
                                    
                                                    
                                    </tr>
                                    <tr><td style="width:30%;" id="nummobile"><label >Numéro(MOBILE MONEY)</label>
                                                <input type="text"  name="nummobile" class="form-control">
                                                    </td>
                                    <td style="width:30%;" id="numcheque"><label>Numéro ch&egrave;que</label>
                                                <input type="text"  name="numcheque" class="form-control">
                                                    </td>
                                    <td style="width:30%;" id="banque"><label>Banque</label>
                                                <input type="text"  name="banque" class="form-control">
                                                    </td>
                                   
                                                    
                                    </tr>
                                
                                </table> --}}
                            </div>
                            <div class="col-md-12">
                            <div class="" id="addedRows" style="display:none;">
                            </div>
                            </div>
                            
                            <div class="col-md-4">
                            <hr><button class="btn btn-success btn-block" style="background:#f00f0f; margin:10px"
                            onClick="javascript:window.location.reload()">ANNULER</button>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-4">
                                <hr><button class="btn btn-success btn-block" style="background:#046b1e; margin:10px" id="direct" onclick="direct()">
                                    ENREGISTRER</button>
                            </div>

                        </div>

                    </div>

                </form>
            </div>
            </div>
        </div>
          <!-- /.col -->
    {{-- </div> --}}
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  {{-- </div> --}}
  

@endsection