<?php
/*
 * File name: SalonDataTable.php
 * Last modified: 2022.02.13 at 23:05:09
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Salon;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class SalonDataTable extends DataTable
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
        $dataTable = $dataTable
            ->editColumn('image', function ($salon) {
                return getMediaColumn($salon, 'image');
            })
            ->editColumn('name', function ($salon) {
                if ($salon['featured']) {
                    return $salon->name . "<span class='badge bg-" . setting('theme_color') . " p-1 m-2'>" . trans('lang.e_service_featured') . "</span>";
                }
                return $salon->name;
            })
            ->editColumn('salon_level.name', function ($salon) {
                return getLinksColumnByRouteName([$salon->salonLevel], "salonLevels.edit", 'id', 'name');
            })
            ->editColumn('users', function ($salon) {
                return getLinksColumnByRouteName($salon->users, 'users.edit', 'id', 'name');
            })->editColumn('address.address', function ($salon) {
                return getLinksColumnByRouteName([$salon->address], 'addresses.edit', 'id', 'address');
            })->editColumn('taxes', function ($salon) {
                return getLinksColumnByRouteName($salon->taxes, 'taxes.edit', 'id', 'name');
            })
            ->editColumn('available', function ($salon) {
                return getBooleanColumn($salon, 'available');
            })
            ->editColumn('closed', function ($salon) {
                return getNotBooleanColumn($salon, 'closed',trans('lang.salon_closed'),trans('lang.salon_open'));
            })
            ->editColumn('accepted', function ($salon) {
                return getBooleanColumn($salon, 'accepted');
            })
            ->editColumn('updated_at', function ($salon) {
                return getDateColumn($salon);
            })
            ->addColumn('action', 'salons.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
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
                'data' => 'image',
                'title' => trans('lang.salon_image'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ],
            [
                'data' => 'name',
                'title' => trans('lang.salon_name'),

            ],
            [
                'data' => 'salon_level.name',
                'name' => 'salonLevel.name',
                'title' => trans('lang.salon_salon_level_id'),

            ],
            [
                'data' => 'users',
                'title' => trans('lang.salon_users'),
                'searchable' => false,
                'orderable' => false
            ],
            [
                'data' => 'phone_number',
                'title' => trans('lang.salon_phone_number'),

            ],
            [
                'data' => 'mobile_number',
                'title' => trans('lang.salon_mobile_number'),

            ],
            [
                'data' => 'address.address',
                'title' => trans('lang.salon_address'),
                'searchable' => false,
                'orderable' => false
            ],
            [
                'data' => 'availability_range',
                'title' => trans('lang.salon_availability_range'),

            ],
            [
                'data' => 'taxes',
                'title' => trans('lang.salon_taxes'),
                'searchable' => false,
                'orderable' => false
            ],
            [
                'data' => 'available',
                'title' => trans('lang.salon_available'),

            ],
            [
                'data' => 'closed',
                'title' => trans('lang.salon_closed'),
                'searchable' => false,

            ],
            [
                'data' => 'accepted',
                'title' => trans('lang.salon_accepted'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.address_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(Salon::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Salon::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.salon_' . $field->name),
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
     * @param Salon $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Salon $model): \Illuminate\Database\Eloquent\Builder
    {
        if (auth()->user()->hasRole('admin')) {
            return $model->newQuery()->with("salonLevel")->with("address")->select("salons.*");
        } else if (auth()->user()->hasRole('salon owner')) {
            return $model->newQuery()
                ->with("salonLevel")
                ->with("address")
                ->join("salon_users", "salon_id", "=", "salons.id")
                ->where('salon_users.user_id', auth()->id())
                ->groupBy("salons.id")
                ->select("salons.*");
        } else {
            return $model->newQuery()->with("salonLevel")->with("address")->select("salons.*");
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
                        ), true),
                    'fixedColumns' => [],
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
        return 'salonsdatatable_' . time();
    }
}
