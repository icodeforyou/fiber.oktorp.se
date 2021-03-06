@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" style="height:45px">
                    <div class="pull-right">
                        <span class="label label-warning">{{ $num_estates }} fastigheter</span>
                        <span class="label label-success">{{ round($confirmed,1) }}% bekräftade</span>
                        <span class="label label-danger">{{ round($canceled,1) }}% avböjt</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table users-table table-condensed">
                        <thead>
                            <tr>
                               <th>Namn</th>
                               <th>E-post</th>
                               <th>Antal fastigheter</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr class="{{ $user->confirmed_interest === 1 ? "success" : ($user->confirmed_interest === 2 ? "danger" : "") }}" onClick="window:location='/users/{{ $user->id }}';" style="cursor:pointer">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td style="text-align: center">{{ count($user->estates) }}</td>
                            </tr>
                       @endforeach
                       </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
