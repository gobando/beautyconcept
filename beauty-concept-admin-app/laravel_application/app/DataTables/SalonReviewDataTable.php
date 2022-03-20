<?php
/*
 * File name: SalonReviewDataTable.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\SalonReview;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class SalonReviewDataTable extends DataTable
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
            ->editColumn('updated_at', function ($salonReview) {
                return getDateColumn($salonReview, 'updated_at');
            })
            ->editColumn('booking.user.name', function ($salonReview) {
                return getLinksColumnByRouteName([$salonReview->booking->user], 'users.edit', 'id', 'name');
            })
            ->editColumn('booking.salon.name', function ($salonReview) {
                return getLinksColumnByRouteName([$salonReview->booking->salon], 'salons.edit', 'id', 'name');
            })
            ->addColumn('action', 'salon_reviews.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = [
            [
                'data' => 'review',
                'title' => trans('lang.salon_review_review'),

            ],
            [
                'data' => 'rate',
                'title' => trans('lang.salon_review_rate'),

            ],
            [
                'data' => 'booking.user.name',
                'title' => trans('lang.salon_review_user_id'),

            ],
            [
                'data' => 'booking.salon.name',
                'title' => trans('lang.salon_review_salon_id'),
            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.salon_review_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(SalonReview::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', SalonReview::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.salon_review_' . $field->name),
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
     * @param SalonReview $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalonReview $model): \Illuminate\Database\Eloquent\Builder
    {
        if (auth()->user()->hasRole('admin')) {
            return $model->newQuery()->with("booking")->select("salon_reviews.*");
        } else if (auth()->user()->hasRole('salon owner')) {
            return $model->newQuery()->with("booking")->join("bookings", "bookings.id", "=", "salon_reviews.booking_id")
                ->join("salon_users", "salon_users.salon_id", "=", "bookings.salon->id")
                ->where('salon_users.user_id', auth()->id())
                ->groupBy('salon_reviews.id')
                ->select('salon_reviews.*');
        } else if (auth()->user()->hasRole('customer')) {
            return $model->newQuery()->join("bookings", "bookings.id", "=", "salon_reviews.booking_id")
                ->where('bookings.user_id', auth()->id())
                ->groupBy('salon_reviews.id')
                ->select('salon_reviews.*');
        } else {
            return $model->newQuery()->with("user")->with("salon")->select("$model->table.*");
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
        return 'salon_reviewsdatatable_' . time();
    }
}
