@extends('layouts.emails')
@section('title')
Garage Booking | {{$company->name}}
@endsection
@section('content')

	<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
		<tr>
			<td align="center" style="padding:0;">
				<table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
					<tr>
						<td align="center" style="padding:40px 0 30px 0;background:{{$company->color}};">
                            <img src="#" alt="" width="300" style="height:auto;display:block;" />
                        </td>
					</tr>
					<tr>
						<td style="padding:36px 30px 42px 30px;">
							<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
								<tr>
									<td style="padding:0 0 15px 0;color:#153643;">
										<h3 style="font-size:16px; margin:0 0 20px 0;font-family:Arial,sans-serif;">{{$company->name}}</h3>
										<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">This is an automated fuel order Vehicle Service Booking Email</p>
									</td>
								</tr>
								<tr>
                                    <td>
                                        <h5 style="margin:0;font-size:17px;line-height:24px;font-family:Arial,sans-serif;">{{$booking->vendor ? $booking->vendor->name : ""}}</h5>
                                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><strong>Booking #:</strong> {{$booking->booking_number}}</p>
                                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Can you provide {{ $booking->service_type }} for {{$booking->vehicle->vehicle_make ? $booking->vehicle->vehicle_make->name : ""}} {{$booking->vehicle->vehicle_model ? $booking->vehicle->vehicle_model->name : ""}} {{$booking->vehicle ? $booking->vehicle->name : ""}} </p>
                                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"> <strong>Requested By: </strong>  <i>{{Auth::user($booking->authorized_by_id) ? Auth::user($booking->authorized_by_id)->name : ""}} {{Auth::user($booking->authorized_by_id) ? Auth::user($booking->authorized_by_id)->surname : ""}} On: {{$booking->created_at}}</i> </p>
                                    </td>
                                </tr>
                               

							</table>
						</td>
					</tr>
					<tr>
						<td style="padding:30px;background: {{$company->color}};">
							<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
								<tr>
									<td style="padding:0;width:50%;" align="left">
									
											<p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">Powered By</p> 
											<br>
										<p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
											&reg;
											Gonyeti TLS {{date('Y')}} | <a href="mailto:info@gonyetitls.com" style="color:#ffffff;text-decoration:underline;">info@gonyetitls.com</a>
										</p>
									
									</td>
									<td style="padding:0;width:50%;" align="right">
										<table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
											<tr>
												<td style="padding:0 0 0 10px;width:38px;">
													<a target="_blank" href="https://www.facebook.com/gonyetitls" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/fb_1.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;"  /></a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

@endsection
