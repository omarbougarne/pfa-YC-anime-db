{{-- inherit from view base --}}
@extends('base')

{{-- create a section to specific code --}}
@section('content')
    @if (!is_null($studios))
        <table id="tabelaStudios" class="table table-striped" style="padding-top: 10px;">
            <thead>
                <tr class="table-dark">
                    <th colspan="4" class="text-center">Studio</th>
                    <th colspan="3" class="text-center">Options</th>
                </tr>
                <tr>
                    <th class="text-center">Logo</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Created</th>
                    <th class="text-center">Dropped</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delet</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studios as $studio)
                    <tr>
                        <td class="align-middle text-center">
                            <img src="{{ url("storage/{$studio->image}") }}" class="img-fluid rounded-circle border border-1" style="width: 6rem; height: auto;">
                        </td>
                        <td class="align-middle text-center">{{ $studio->name }}</td>
                        <td class="align-middle text-center">{{ $studio->established }}</td>
                        <td class="align-middle text-center">
                            <a href="{{ route('studios.show', $studio->id) }}" class="btn btn-link" style="text-decoration: none;"><i class="fa-solid fa-circle-info text-info"></i> Fropped</a>
                        </td>
                        <td class="align-middle text-center">
                            <a href="{{ route('studios.edit', $studio->id) }}" class="btn btn-link" style="text-decoration: none;"><i class="fa-solid fa-pen-to-square text-primary"></i> Edit</a>
                        </td>
                        <td class="align-middle text-center">
                            <form action="{{ route('studios.destroy', $studio->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link" style="text-decoration: none;" type="submit">
                                    <i class="fa-solid fa-trash text-danger"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h3>No studio was found!</h3>
    @endif
@endsection
