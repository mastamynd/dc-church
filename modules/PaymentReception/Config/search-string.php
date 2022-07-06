<?php
	return [
		'Modules\PaymentReception\Models\PaymentReception' => [
			'columns' => [
				'id',
				'reference' => ['searchable' => true],
				'church_id' => [
					'route' => ['churches.index', 'search=enabled:1']
				],
				'created_at' => ['searchable' => true, 'date' => true],
				'status' => [
					'status' => ['Pending'=>'Pending', 'Reconciled'=>'Reconciled']
				],
				'payment_mode' => [
					'values' => ['mpesa'=>'M-Pesa', 'cash'=>'Cash', 'bank deposit'=>'Bank Deposit']
				]
			]
		]
	];