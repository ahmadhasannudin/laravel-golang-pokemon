<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pokemon - Ahmad Hasanudin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    </script>

    <link rel="stylesheet" href="{{asset('storage/app.css')}}">
</head>

<body>
    <div id="app">
        <img class="logo" src="{{asset('logo.png')}}" alt="Pokemon">
        <div id="action" class="text-center">
            <button type="button" class="btn btn-black" data-bs-toggle="modal"
                data-bs-target="#pokemonListModal">List</button>
            <button type="button" class="btn btn-black" data-bs-toggle="modal"
                data-bs-target="#captureModal">Capture</button>
        </div>
        <div class="row pokemon-div pt-3">
            @foreach ($data as $v)
                <div class="col-sm-3 p-1">
                    <div class="pokemon-cell">
                        <input type="hidden" name="id" readonly value="{{$v['id']}}">
                        <img src="{{$v['sprites']['front_default']}}" alt="{{$v['name']}}">
                        <div class="detail pb-2">
                            <h3>{{$v['name']}}</h3>
                            <h5>{{$v['nickname'] == '' ? '-' : $v['nickname']}}</h5>
                            <p>height: {{$v['height']}} | weight: {{$v['weight']}}</p>
                            <div>
                                <button type="button" class="btn btn-secondary pokemon-button"
                                    onclick="nickname({{$v['id']}})">Nick
                                    Name</button>
                                <button type="button" class="btn btn-secondary pokemon-button"
                                    onclick="release({{$v['id']}})">Release</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="captureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Capture Pokemon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCapture">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">Pokemon ID</label>
                            </div>
                            <div class="col-auto">
                                <input type="number" name="id" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-black" data-bs-toggle="modal"
                                    data-bs-target="#captureModal">Capture</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pokemonListModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Available Pokemon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="availablePokemon">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="releaseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Release Pokemon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formRelease">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">Release Number</label>
                            </div>
                            <div class="col-auto">
                                <input type="hidden" readonly name="id" class="form-control">
                                <input type="number" name="release" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-black" data-bs-toggle="modal"
                                    data-bs-target="#releaseModal">Release</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="nicknameModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Change Nick Name Pokemon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNickname">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">New Nick Name</label>
                            </div>
                            <div class="col-auto">
                                <input type="hidden" readonly name="id" class="form-control">
                                <input type="text" name="nickname" class="form-control">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-black" data-bs-toggle="modal"
                                    data-bs-target="#nicknameModal">Change</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detail-pokemon-name">Change Nick Name Pokemon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detail-pokemon">

                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toast-message"></strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#formCapture').on('submit', function (e) {
            e.preventDefault();
            var id = $(this).find('input[name="id"]').val()
            $.ajax({
                type: "POST",
                url: "{{route('pokemon.store', ':id')}}".replace(':id', id),
                success: function (msg) {
                    toast('Success');
                    setTimeout(() => {
                        location.reload()
                    }, 1000);
                },
                error: function (msg) {
                    toast(msg.responseJSON.message);
                }
            });
        });

        $('#pokemonListModal').on('show.bs.modal', function (e) {
            $.ajax({
                type: "GET",
                url: "{{route('pokemon.list')}}",
                success: function (msg) {
                    $('#availablePokemon').html('')
                    for (let i = 0; i < msg.data.length; i++) {
                        $('#availablePokemon').append(`<div class="col-sm-6 pokemon-ls">${msg.data[i].id}.${msg.data[i].name}</div>`)
                    }
                    console.log(msg)
                },
                error: function (msg) {
                    console.log(msg)
                }
            });
        });

        function release(id) {
            $('#formRelease').find('input[name="id"]').val(id)
            $('#releaseModal').modal('show')
        }
        $('#formRelease').on('submit', function (e) {
            e.preventDefault();
            var id = $(this).find('input[name="id"]').val()
            $.ajax({
                type: "DELETE",
                url: "{{route('pokemon.delete', ':id')}}".replace(':id', id) + '?release=' + $(this).find('input[name="release"]').val(),
                success: function (msg) {
                    toast('Success');
                    setTimeout(() => {
                        location.reload()
                    }, 1000);
                },
                error: function (msg) {
                    toast(msg.responseJSON.message);
                }
            });
        });

        function nickname(id) {
            $('#formNickname').find('input[name="id"]').val(id)
            $('#nicknameModal').modal('show')
        }
        $('#formNickname').on('submit', function (e) {
            e.preventDefault();
            var id = $(this).find('input[name="id"]').val()
            $.ajax({
                type: "PUT",
                url: "{{route('pokemon.update', ':id')}}".replace(':id', id) + '?nickname=' + $(this).find('input[name="nickname"]').val(),
                success: function (msg) {
                    toast('Success');
                    setTimeout(() => {
                        location.reload()
                    }, 1000);
                },
                error: function (msg) {
                    toast(msg.responseJSON.message);
                }
            });
        });

        const pokemonButton = document.querySelectorAll('.pokemon-button')
        pokemonButton.forEach((v, i) => {
            v.addEventListener('click', function (e) {
                e.stopPropagation()
            })
        })

        const pokemonCell = document.querySelectorAll('.pokemon-cell')
        pokemonCell.forEach((v, i) => {
            v.addEventListener('click', function () {
                $.ajax({
                    type: "GET",
                    url: "{{route('pokemon.detail', ':id')}}".replace(':id', pokemonCell.item(i).querySelector('input[name="id"]').value),
                    success: function (msg) {
                        $('#detail-pokemon-name').html(msg.data.name)
                        $('#detail-pokemon').html('')
                        $('#detail-pokemon').append(`
                                <div class="row pb-2">
                                    <div class="col-6">
                                        <img src="${msg.data.sprites.front_default}" alt="${msg.data.name}">
                                    </div>
                                    <div class="col-6">
                                        <img src="${msg.data.sprites.back_default}" alt="${msg.data.name}">
                                    </div>
                                </div>
                                <div class="detail pb-2">
                                    <h3>${msg.data.name}</h3>
                                    <h5>${msg.data.nickname ?? '-'}</h5>
                                    <p>height: ${msg.data.height} | weight: ${msg.data.weight}</p>
                                </div>
                        `)
                        // $('#detail-pokemon').append(`<img src="${msg.data.sprites.front_default}" alt="${msg.data.name}">`)
                        // $('#detail-pokemon').append(`<p>height: ${msg.data.height} | weight: ${msg.data.weight}</p>`)
                        $('#detailModal').modal('show')
                    },
                    error: function (msg) {
                        console.log(msg)
                    }
                });
            })
        })

        const toastLiveExample = document.getElementById('liveToast')
        function toast(message) {
            const toastMessage = document.getElementById('toast-message')
            toastMessage.innerHTML = ''
            toastMessage.innerHTML = message
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastBootstrap.show()
        }
    </script>
</body>

</html>