@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="efectivo-tab" data-toggle="tab" href="#efectivo" role="tab" aria-controls="efectivo" aria-selected="true">EFECTIVO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="deposito-qr-tab" data-toggle="tab" href="#deposito-qr" role="tab" aria-controls="deposito-qr" aria-selected="false">DEPOSITO/QR</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tarjeta-tab" data-toggle="tab" href="#tarjeta" role="tab" aria-controls="tarjeta" aria-selected="false">TARJETA</a>
            </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="efectivo" role="tabpanel" aria-labelledby="efectivo-tab">
                    <div class="row">
                        <div class="col-12 col-sm-8">
                            <div class="card">
                                <div class="card-header" style="width: 100%; background-color: #1d2736">
                                    <div class="row" style="width: 100%;">
                                        <div class="col-12 col-sm-8">
                                            <h3 class="card-title" style="color: white; font-weight: bold;">Efectivo</h3>
                                        </div>
                                        <div class="col-12 col-sm-4" style="text-align: right;">
                                            <button id="addcajaEfectivos" class="btn btn-primary">
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="row">
                                            <div class="col-12 col-sm-12" style="width: 100%; background: #F5F7F8; padding-top: 10px;">
                                                <div class="row" style="background: #F5F7F8;">
                                                    <div class="col-md-11">
                                                        <div class="row" style="padding-bottom: 10px">
                                                            <div class="col-md-3">
                                                                <select name="DateCajaEfectivo" id="DateCajaEfectivo" class="form-control">
                                                                    <option value="DiarioCajaEfectivo">Diario</option>
                                                                    <option value="MensualidadCajaEfectivo">Todo El Mes</option>
                                                                    <option value="RangoCajaEfectivo">Rango</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2" id="DiaCajaEfectivoContainer">
                                                                <select name="DiaCajaEfectivo" id="DiaCajaEfectivo" class="form-control"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="MesCajaEfectivoContainer">
                                                                <select name="MesCajaEfectivo" id="MesCajaEfectivo" class="form-control"><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="AnioCajaEfectivoContainer">
                                                                <select name="AnioCajaEfectivo" id="AnioCajaEfectivo" class="form-control"><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="FechaInicioContainerCajaEfectivo" style="display: none;">
                                                                <input type="date" name="fechaInicioCajaEfectivo" id="fechaInicioCajaEfectivo" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                            <div class="col-md-3" id="FechaFinContainerCajaEfectivo" style="display: none;">
                                                                <input type="date" name="fechaFinCajaEfectivo" id="fechaFinCajaEfectivo" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="row">
                                                    <div class="col-md-12" style="border-top:2px solid #E6E6E6;">
                                                        <div class="row pt-2">
                                                            <!-- Ingreso -->
                                                            <div class="col-md-4" style="border-right:2px solid #E6E6E6; border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">INGRESO <br>
                                                                    <span id="IngresoEfectivo" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                            <!-- Egreso -->
                                                            <div class="col-md-4" style="border-right:2px solid #E6E6E6; border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">EGRESO <br>
                                                                    <span id="EgresoEfectivo" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                            <!-- Total -->
                                                            <div class="col-md-4" style="border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">TOTAL <br>
                                                                    <span id="TotalEfectivo" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="table-responsive" style="overflow-y: scroll; max-height: 500px">
                                                    <table class="table table-vcenter card-table" id="tabla-caja-Efectivo">
                                                        <thead style="position: sticky; top: 0; z-index: 1;">
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Sucursal</th>
                                                                <th>Categoria</th>
                                                                <th>Descripcion</th>
                                                                <th>Fecha</th>
                                                                <th>Ingreso</th>
                                                                <th>Egreso</th>
                                                                <th>Saldo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Se llena dinámicamente -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="card" id="detalleMovimientoCajaEfectivo">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="deposito-qr" role="tabpanel" aria-labelledby="deposito-qr-tab"> 
                    <div class="row">
                        <div class="col-12 col-sm-8">
                            <div class="card">
                                <div class="card-header" style="width: 100%; background-color: #1d2736">
                                    <div class="row" style="width: 100%;">
                                        <div class="col-12 col-sm-8">
                                            <h3 class="card-title" style="color: white; font-weight: bold;">Deposito/QR</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="row">
                                            <div class="col-12 col-sm-12" style="width: 100%; background: #F5F7F8; padding-top: 10px;">
                                                <div class="row" style="background: #F5F7F8;">
                                                    <div class="col-md-11">
                                                        <div class="row" style="padding-bottom: 10px">
                                                            <div class="col-md-3">
                                                                <select name="DateCajaDeposito" id="DateCajaDeposito" class="form-control">
                                                                    <option value="DiarioCajaDeposito">Diario</option>
                                                                    <option value="MensualidadCajaDeposito">Todo El Mes</option>
                                                                    <option value="RangoCajaDeposito">Rango</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2" id="DiaCajaDepositoContainer">
                                                                <select name="DiaCajaDeposito" id="DiaCajaDeposito" class="form-control"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</	option><Option value="20">20</Option><Option value="21">21</Option><Option value="22">22</Option><Option value="23">23</Option><Option value="24">24</Option><Option value="25">25</Option><Option value="26">26</Option><Option value="27">27</Option><Option value="28">28</Option><Option value="29">29</Option><Option value="30">30</Option></select>
                                                            </div>
                                                            <div class="col-md-3" id="MesCajaDepositoContainer">
                                                                <select name="MesCajaDeposito" id="MesCajaDeposito" class="form-control"><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="AnioCajaDepositoContainer">
                                                                <select name="AnioCajaDeposito" id="AnioCajaDeposito" class="form-control"><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="FechaInicioContainerCajaDeposito" style="display: none;">
                                                                <input type="date" name="fechaInicioCajaDeposito" id="fechaInicioCajaDeposito" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                            <div class="col-md-3" id="FechaFinContainerCajaDeposito" style="display: none;">
                                                                <input type="date" name="fechaFinCajaDeposito" id="fechaFinCajaDeposito" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="row">
                                                    <div class="col-md-12" style="border-top:2px solid #E6E6E6;">
                                                        <div class="row pt-2">
                                                            <!-- Ingreso -->
                                                            <div class="col-md-4" style="border-right:2px solid #E6E6E6; border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">INGRESO <br>
                                                                    <span id="IngresoDeposito" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                            <!-- Total -->
                                                            <div class="col-md-4" style="border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">TOTAL <br>
                                                                    <span id="TotalDeposito" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="table-responsive" style="overflow-y: scroll; max-height: 500px">
                                                    <table class="table table-vcenter card-table" id="tabla-caja-Deposito">
                                                        <thead style="position: sticky; top: 0; z-index: 1;">
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Sucursal</th>
                                                                <th>Categoria</th>
                                                                <th>Descripcion</th>
                                                                <th>Fecha</th>
                                                                <th>Ingreso</th>
                                                                <th>Egreso</th>
                                                                <th>Saldo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Se llena dinámicamente -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="card" id="detalleMovimientoCajaDeposito">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tarjeta" role="tabpanel" aria-labelledby="tarjeta-tab"> 
                    <div class="row">
                        <div class="col-12 col-sm-8">
                            <div class="card">
                                <div class="card-header" style="width: 100%; background-color: #1d2736">
                                    <div class="row" style="width: 100%;">
                                        <div class="col-12 col-sm-8">
                                            <h3 class="card-title" style="color: white; font-weight: bold;">Tarjeta/QR</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="row">
                                            <div class="col-12 col-sm-12" style="width: 100%; background: #F5F7F8; padding-top: 10px;">
                                                <div class="row" style="background: #F5F7F8;">
                                                    <div class="col-md-11">
                                                        <div class="row" style="padding-bottom: 10px">
                                                            <div class="col-md-3">
                                                                <select name="DateCajaTarjeta" id="DateCajaTarjeta" class="form-control">
                                                                    <option value="DiarioCajaTarjeta">Diario</option>
                                                                    <option value="MensualidadCajaTarjeta">Todo El Mes</option>
                                                                    <option value="RangoCajaTarjeta">Rango</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2" id="DiaCajaTarjetaContainer">
                                                                <select name="DiaCajaTarjeta" id="DiaCajaTarjeta" class="form-control"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</	option><Option value="20">20</Option><Option value="21">21</Option><Option value="22">22</Option><Option value="23">23</Option><Option value="24">24</Option><Option value="25">25</Option><Option value="26">26</Option><Option value="27">27</Option><Option value="28">28</Option><Option value="29">29</Option><Option value="30">30</Option></select>
                                                            </div>
                                                            <div class="col-md-3" id="MesCajaTarjetaContainer">
                                                                <select name="MesCajaTarjeta" id="MesCajaTarjeta" class="form-control"><option value="1">Enero</option><option value="2">Febrero</option><option value="3">Marzo</option><option value="4">Abril</option><option value="5">Mayo</option><option value="6">Junio</option><option value="7">Julio</option><option value="8">Agosto</option><option value="9">Septiembre</option><option value="10">Octubre</option><option value="11">Noviembre</option><option value="12">Diciembre</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="AnioCajaTarjetaContainer">
                                                                <select name="AnioCajaTarjeta" id="AnioCajaTarjeta" class="form-control"><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option></select>
                                                            </div>
                                                            <div class="col-md-3" id="FechaInicioContainerCajaTarjeta" style="display: none;">
                                                                <input type="date" name="fechaInicioCajaTarjeta" id="fechaInicioCajaTarjeta" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                            <div class="col-md-3" id="FechaFinContainerCajaTarjeta" style="display: none;">
                                                                <input type="date" name="fechaFinCajaTarjeta" id="fechaFinCajaTarjeta" class="form-control" min="2026-04-01" max="2026-04-30">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="row">
                                                    <div class="col-md-12" style="border-top:2px solid #E6E6E6;">
                                                        <div class="row pt-2">
                                                            <!-- Ingreso -->
                                                            <div class="col-md-4" style="border-right:2px solid #E6E6E6; border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">INGRESO <br>
                                                                    <span id="IngresoTarjeta" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                            <!-- Total -->
                                                            <div class="col-md-4" style="border-bottom:1px solid #E6E6E6; padding:10px;">
                                                                <span style="color:#7F8487;">TOTAL <br>
                                                                    <span id="TotalTarjeta" style="color:#2C3333; font-weight:bold; font-size:22px;">Cargando info...</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12" style="width: 100%; margin:0; border:1px solid #E6E6E6; border-top:none;">
                                                <div class="table-responsive" style="overflow-y: scroll; max-height: 500px">
                                                    <table class="table table-vcenter card-table" id="tabla-caja-Tarjeta">
                                                        <thead style="position: sticky; top: 0; z-index: 1;">
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Sucursal</th>
                                                                <th>Categoria</th>
                                                                <th>Descripcion</th>
                                                                <th>Fecha</th>
                                                                <th>Ingreso</th>
                                                                <th>Egreso</th>
                                                                <th>Saldo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Se llena dinámicamente -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="card" id="detalleMovimientoCajaTarjeta">
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<style>
    #tabla-caja-Deposito thead {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #f8f9fa; /* gris claro Bootstrap */
    }

    .selected-row {
        background-color: #F9E7B2 !important; /* azul claro */
    }

</style>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('js/cajaefectivo.js') }}"></script>
<script src="{{ asset('js/cajadeposito.js') }}"></script>
<script src="{{ asset('js/cajatarjeta.js') }}"></script>
@endsection

