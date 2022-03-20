<?php
/*
 * File name: WalletDataTable.php
 * Last modified: 2021.11.24 at 19:20:10
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Wallet;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class WalletDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        return $dataTable
            ->editColumn('updated_at', function ($wallet) {
                return getDateColumn($wallet, 'updated_at');
            })
            ->editColumn('enabled', function ($wallet) {
                return getBooleanColumn($wallet, 'enabled');
            })
            ->editColumn('balance', function ($wallet) {
                return getPriceColumn($wallet, 'balance', $wallet->currency);
            })
            ->editColumn('currency.name', function ($wallet) {
                if (isset($wallet->currency)) {
                    return $wallet->currency->name;
                } else {
                    return "";
                }
            })
            ->editColumn('user.name', function ($wallet) {
                return getLinksColumnByRouteName([$wallet->user], 'users.edit', 'id', 'name');
            })
            ->addColumn('action', 'wallets.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'name',
                'title' => trans('lang.wallet_name'),

            ],
            [
                'data' => 'balance',
                'title' => trans('lang.wallet_balance'),

            ],
            [
                'data' => 'currency.name',
                'name' => 'currency',
                'title' => trans('lang.wallet_currency'),
            ],
            (auth()->check() && auth()->user()->hasRole('admin')) ? [
                'data' => 'user.name',
                'title' => trans('lang.wallet_user_id'),

            ] : null,
            [
                'data' => 'enabled',
                'title' => trans('lang.wallet_enabled'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.wallet_updated_at'),
                'searchable' => false,
            ]
        ];
        $columns = array_filter($columns);
        $hasCustomField = in_array(Wallet::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Wallet::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.wallet_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param Wallet $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wallet $model)
    {
        if (auth()->check() && !auth()->user()->hasRole('admin')) {
            return $model->newQuery()->where('wallets.user_id', auth()->id())->with("user")->select("$model->table.*");
        } else {
            return $model->newQuery()->with("user")->select("$model->table.*");
        }

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'walletsdatatable_' . time();
    }
}
