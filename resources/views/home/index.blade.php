@extends('layouts.app-master')

@section('content')


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <div class=" pt-1 ">
        @auth

            @if ($groups && $groups->count() == 0)
                <p>Чтобы начать ведение учета, вам нужно состоять в группе с другими людьми (с которыми у вас будет
                    совместный бюджет)</p>
                <p>Вы можете вступить в группу, созданную другим человеком. Для этого он должен прислать вам ссылку на
                    вступление в группу</p>
                <p>Либо вы можете создать группу, после чего сможете пригласить в нее других уастников</p>
                <form action="/create_group" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                               placeholder="Имя группы" required="required">
                        @if ($errors->has('name'))
                            <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                        @endif
                        <div class="input-group-append">
                            <button type="submit" href="#" class="btn btn-outline-dark">Создать</button>
                        </div>
                    </div>
                </form>
            @endif

            @foreach($groups as $group)

                <h3>{{$group->name}}</h3>
                <div><strong>{{$group->budget()}}</strong>
{{--                    (из них {{auth()->user()->personalBudget($group)}} ваших личных)--}}
                </div>
                    <canvas id="money_{{$group->id}}"></canvas>
                <div>
                    @php
                        $labels = [];
                        $data = [];
                    @endphp
                    @foreach($group->users as $user)
                        @php
                            $labels[] = $user->username;
                            $data[] = $user->personalBudget($group);
                        @endphp
{{--                        <div class="col-sm-12">--}}
{{--                            <span>--}}
{{--                                {{$user->username}}--}}
{{--                            </span>--}}
{{--                            @if($user->multiplicator > 1)--}}
{{--                               <span class="badge badge-info" title="количество человек"> x{{$user->multiplicator}}</span>--}}
{{--                            @endif--}}
{{--                            /--}}
{{--                            {{$user->personalBudget($group)}}--}}
{{--                        </div>--}}

                    @endforeach
                </div>
                <script type="text/javascript">

                    var labels = {{Js::from($labels)}};
                    var users = {{Js::from($data)}};

                    const data = {
                        labels: labels,
                        datasets: [{
                            // label: 'Распределение бюджета',
                            backgroundColor: 'rgb(109,255,99)',
                            borderColor: 'rgb(109,255,99)',
                            data: users,
                        }]
                    };

                    const config = {
                        type: 'bar',
                        data: data,
                        plugins: [ChartDataLabels],
                        options: {
                            plugins:{
                                legend: {
                                    display: false
                                }
                            },
                            scales:{
                                x:{
                                    display: false
                                }
                            },
                            indexAxis: 'y',
                            // barThickness: 20

                        }
                    };

                    const myChart = new Chart(
                        document.getElementById("money_{{$group->id}}"),
                        config
                    );

                </script>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-toggle="tab" data-target="#add" role="tab">Пополнить</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link active" data-toggle="tab" data-target="#spend"
                           role="tab">Потратить</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-toggle="tab" data-target="#group" role="tab">Группа</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade  pt-4 pb-4 " id="add" role="tabpanel">
                        <form action="/make_deposit/{{$group->id}}" method="POST" class="form-inline row">
                            @csrf
                            <div class="col-sm-4 mb-2">
                                <input class="form-control" name="amount" type="text" placeholder="Сумма" value="{{old('amount')}}">
                            </div>
                            <div class="col-sm-4 mb-2">
                                <select id="inputState" name="user_id" class="form-control" value="{{old('user_id')}}">
                                    <option value="" selected>Вноситель</option>
                                    @foreach($group->users as $user)
                                        <option value="{{$user->id}}">{{$user->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-outline-primary">Внести</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade  show active pt-4 pb-4" id="spend" role="tabpanel">
                        <form action="/spend_money/{{$group->id}}" method="POST" class="form-inline row">
                            @csrf
                            <div class="col-sm-12 col-md-4">
                                <input class="form-control mb-2" name="amount" type="text" placeholder="Общая сумма" value="{{old('amount')}}">
                                <input class="form-control" name="comment" type="text" placeholder="Общий комментарий" value="{{old('comment')}}">
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <table class="table">
                                    <tr>
                                        <td title="если отмечено, то пользователь принимает участие в оплате счета поровну с остальными пользователями">
                                            ?
                                        </td>
                                        <td>имя</td>
                                        <td>сумма</td>
                                        <td>комментарий</td>
                                    </tr>
                                    @foreach($group->users as $user)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox" checked
                                                       name="data[{{$user->id}}][common]">
                                            </td>
                                            <td>
                                                {{$user->username}}
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                       name="data[{{$user->id}}][personal_amount]" value="{{old('data.' . $user->id . '.personal_amount')}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                       name="data[{{$user->id}}][personal_comment]">
                                            </td>
                                        </tr>

                                    @endforeach
                                </table>

                            </div>
                            <div class="col-sm-12 col-md-3">
                                <button type="submit" class="btn btn-outline-primary">Отправить</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade  pt-4 pb-4 " id="group" role="tabpanel">
                        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-1">

                            @foreach($group->users as $user)
                                <div class="col-sm-12 col-md-4 col-xl-3">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between">
                                                <span>
                                                {{$user->username}}
                                                </span>
                                            @if(auth()->user()->isGroupAdmin($group))
                                                <form action="/mult_change/{{$group->id}}/{{$user->id}}"
                                                      class="form-inline col-sm-4 col-md-3 mb-0 mr-2"
                                                      method="POST">
                                                    @csrf

                                                    <select name="multiplicator" class="form-control"
                                                            onchange="this.parentNode.submit()">
                                                        <option value="1"
                                                                @if($user->multiplicator == 1) selected @endif>1 чел
                                                        </option>
                                                        <option value="2"
                                                                @if($user->multiplicator == 2) selected @endif>2 чел
                                                        </option>
                                                    </select>
                                                </form>
                                            @endif
                                            @if(auth()->user()->isGroupAdmin($group))
                                                <form action="/ru/{{$group->id}}/{{$user->id}}"
                                                      method="POST"
                                                      class="w-auto mb-0">
                                                    @csrf
                                                    <input type="hidden" name="aa">
                                                    <button type="submit" class="close" title="Удалить из группы">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">Баланс: {{$user->personalBudget($group)}}</p>

                                        </div>


                                        <div class="card-footer d-flex justify-content-center">
                                            @if(auth()->user()->isGroupAdmin($group))
                                                <form action="/toggle_admin/{{$group->id}}" method="POST"
                                                      class="w-auto mb-0">
                                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                                    @csrf
                                                    @if(!$user->isGroupAdmin($group))
                                                        <button type="submit" href=""
                                                                class="btn btn-sm btn-outline-success">
                                                            Сделать админом
                                                        </button>
                                                    @else
                                                        @if($user->id !== $group->owner)
                                                            <button type="submit" href=""
                                                                    class="btn btn-sm btn-outline-warning">
                                                                Убрать из админов
                                                            </button>
                                                        @endif
                                                    @endif
                                                </form>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>


                <p>Лог</p>
                <div class="accordion" id="accordionExample">

                    {{$mainOperations = \App\Models\Operation::where('group_id', $group->id)->whereNotNull('type')->orderByDesc('id')->paginate(50)}}
                    @foreach($mainOperations as $operation)
                        <div class="logLine shadow-sm">
                            @if($operation->type == 'debit')
                                <div class="text-success mb-1 p-1 d-flex justify-content-between">
                                    <div>
                                        <small
                                            class="text-muted">{{explode(' ', $operation->created_at)[0]}}</small> {{$operation->user->username}}
                                        : {{$operation->comment}}
                                        {{$operation->amount}}
                                    </div>
                                    <div>
                                        <a href="#">del</a>
                                    </div>
                                </div>

                            @endif
                            @if($operation->type == 'credit')

                                <div class=" mb-1 p-1 d-flex justify-content-between">
                                    <div id="heading{{$operation->id}}"
                                         data-toggle="collapse"
                                         data-target="#collapse{{$operation->id}}"
                                         class="flex-grow-1 logLine__header collapsed">

                                        <small
                                            class="text-muted">{{explode(' ', $operation->created_at)[0]}}</small> {{$operation->comment}}
                                        {{$operation->amount}}
                                    </div>
                                    <div>
                                        <a href="#">del</a>
                                    </div>

                                </div>
                                <div id="collapse{{$operation->id}}" class="collapse " data-parent="#accordionExample">
                                    <div class="table-responsive">
                                        <table class="table ">
                                            @foreach($group->operations->whereNull('type')->where('operation_id', $operation->id) as $op)
                                                <tr @if($op->user_id == auth()->user()->id) class="text-primary" @endif>
                                                    <td>{{$op->user->username}}</td>
                                                    <td>{{$op->amount}}</td>
                                                    <td>{{$op->comment}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>

                            @endif
                        </div>
                    @endforeach

                </div>
                {{$mainOperations->links()}}


                @if($group->owner == auth()->user()->id)
                    @if($group->token)
                        <a href="{{route('join_group', ['group' => $group->id]) . '?token=' . $group->token}}">Ссылка
                            для приглашения в группу</a>
                    @else
                        <a href="#">Создать ссылку на приглашение в группу</a>
                    @endif
                @endif
                <a href="{{route('leave_group', ['group' => $group->id])}}" class="btn btn-outline-danger">Покинуть
                    группу</a>
                @if($group->owner == auth()->user()->id)
                    <a href="#" class="btn btn-danger">Удалить группу</a>
                @endif
            @endforeach
        @endauth

        @guest
            <h2 id="-">Совместный бюджет</h2>
            <p> Ведение общего бюджета для группы людей.</p>
            <p>Смысл всего:</p>
            <p>Есть группа людей, ведущих совместный бюджет. Требуется прозрачность движения денег в/из бюджета. Сервис
                позволяет вести учет личных средств каждого участника группы.</p>
            <h3 id="-">Как это работает:</h3>
            <p>Проще всего представить следующую ситуацию: есть одна банковская карта и несколько человек,
                проживающих/путешествующих/закупающихся вместе.
                Время от времени на карту заносятся деньги кем-то из группы, с карты деньги тратятся при походах по
                магазинам, рынкам. Обычно траты общие (то есть сумму покупки надо поделить на количество человек в
                группе), но иногда в общую покупку вклиниваются товары, предназначенные только для конкретного члена
                группы, и тогда эту сумму надо вычленить из общей суммы перед дележкой.</p>
            <ul>
                <li>Сколько денег в данный момент на общем счету? (можно глянуть баланс карты, но не у каждого члена
                    группы может быть доступ к карте, например),
                </li>
                <li>Сколько из этой суммы принадлежит конкретному участнику?</li>
                <li>Кто сколько потратил за определенный период?
                    Чтобы оперативно получать ответы на подобные вопросы и существует этот сервис.
                </li>
            </ul>
            <p>Коротко сущность сервиса можно описать так:
                Несколько пользователей могут вступить в группу, создатель группы может назначать админов группы, админы
                могут вносить деньги в бюджет и разносить траты по участникам группы (траты автоматически распределяются
                в равных долях, есть возможность вычленить из каждой совместной траты сумму, относящуюся к конкретному
                члену группы).
                У всех членов группы есть доступ к логам группы и к информации о текущем финансовом состоянии группы:
                сколько денег на общем счету и какова доля каждого участника группы.</p>
            <h2 id="-">Сущности:</h2>
            <ol>
                <li>Пользователи.

                    <p>Могут</p>
                    <ul>
                        <li>зарегистрироваться,</li>
                        <li>создать группу,</li>
                        <li>вступить в группу по ссылке</li>
                        <li>находиться одновременно в нескольких группах</li>
                        <li>просматривать свой бюджет в группе</li>
                        <li>создатель группы имеет права админа, может назначать доп. админов из числа участников
                            группы
                        </li>
                        <li>
                            админ группы может распределять бюджет
                        </li>
                    </ul>
                </li>
                <li>
                    <p>Группы</p>
                    <ul>
                        <li><p>Группа создается любым пользователем</p>
                        </li>
                        <li>Группа может быть связана с одним бюджетом</li>
                        <li>В группе может быть неограниченное количество пользователей</li>
                    </ul>

                </li>
                <li>Операции
                    <ul>
                        <li>В какой бюджет какой пользователь сколько денег(+-), комментарий</li>
                    </ul>
                </li>
            </ol>

        @endguest
    </div>
@endsection

<style>
    .logLine__header:not(.collapsed) {
        font-weight: 600;
    }
</style>
