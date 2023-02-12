<div id="cancel-now" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('panel-transactions.cancelNow') }}" method="POST">

                    @csrf

                    <input type="hidden" name="transfer_id" value="">

                    <h4 class="mb-5">Вы уверенны?</h4>

                    <div class="d-flex w-100 justify-content-evenly">
                        <button type="submit" class="btn btn-primary w-50">Да</button>
                        <button type="submit" class="btn btn-secondary w-50 ms-3">Нет</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
