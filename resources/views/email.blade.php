@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Skapa nytt utskick</div>
                <div class="panel-body">
                    <h2>Ange innehåll</h2>
                    <form action="/emails/{{ isset($email) ? "edit/" . $email->id : "new" }}" method="post">
                        
                        <div class="form-group">
                            <label>Ämne</label>
                            <input class="form-control" type="text" name="subject" value="{{ isset($email) ? $email->subject : "" }}" />
                        </div>

                        <div class="form-group">
                            <label>Mailtext</label>
                            <textarea id="contentEditable" class="form-control" rows="15" name="email">{{ isset($email) ? $email->email_content : "" }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Välj mottagare</label>
                            <select name="recipients[]" multiple class="form-control">
                                @foreach ($users as $user)
                                <option{{ isset($email) && in_array($user->email, $email->recipients) ? " selected" : "" }} value="{{{ $user->email }}}">{{{ $user->email }}} ({{{ $user->name }}})</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="_token" value="{{{ Session::token() }}}"/>

                        <button type="submit" class="btn btn-primary">Spara</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
