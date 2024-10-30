<?php
include('csl-corona-data.php');
include('csl-utils.php');

class csl_CoronaCharts { 

	private $api = 'https://9kzzzfwgnwgef8dc.disease.sh/v2/';

	private function chart_data($data) {
		$cases = array();
		$deaths = array();
		$recovered = array();
		$record = array();
		
		foreach($data->cases as $key => $value) {
			$cases[] = array(
				'date' => $key,
				'cases' => $value,
			);
		}

		foreach($data->deaths as $key => $value) {
			$deaths[] = array(
				'date' => $key,
				'deaths' => $value,
			);
		}

		foreach($data->recovered as $key => $value) {
			$recovered[] = array(
				'date' => $key,
				'recovered' => $value,
			);
		}
		$i = 0;
		for($i = 0; $i < count($cases); $i++) {
			$record[] = array(
				'date' => date('m-d-Y', strtotime($cases[$i]['date'])),
				'cases' => $cases[$i]['cases'],
				'deaths' => $deaths[$i]['deaths'],
				'recovered' => $recovered[$i]['recovered'],

			);
		}

		return $record;
	}

	public function csl_create_chart($atts) {
		extract( shortcode_atts( array (
			'id' =>  esc_html__('chart_1', 'csl-corona-stats'),
			'country' => esc_html__('USA', 'csl-corona-stats'),
			'type' => esc_html__('bar', 'csl-corona-stats')
		), $atts ) );	
			// url escapped 
			$data_class = new csl_CoronaData;
			$cs_data = $data_class->historical_data($country);
			$chartData= array();	
			$chartData = $this->chart_data($cs_data->timeline);

			$chartData = array_slice($chartData, -10);

			$labels = "";
			$stats1 = "";
			$stats2 = "";
			$stats3 = "";
			$stats4 = "";
			$stats5 = "";
			$count = count($chartData);
			for($i = 0; $i < $count; $i++) {
				// prepare comma separated values for javascript chart
				$labels .= sprintf("%s,", $chartData[$i]['date'] );
				$stats1 .= sprintf("%s,", $chartData[$i]['cases'] );
				$stats2 .= sprintf("%s,", $chartData[$i]['deaths']);
				$stats4 .= sprintf("%s,", $chartData[$i]['recovered']);
			} ?>
			<div class="chart-wrap">
			<h3><?php echo esc_html( $country ) ?></h3>
			<canvas id="<?php echo esc_html($id) ?>"></canvas>
				<div class="do-chart" canvas-id="<?php echo esc_html( $id ) ?>" chart-type="<?php echo esc_html ($type ) ?>" data-count="3">
					<span><?php echo esc_html( rtrim($stats1, ',')) ?></span>
					<span><?php echo esc_html( rtrim($stats2, ',')) ?></span>
					<span><?php echo esc_html( rtrim($stats4, ',')) ?></span>
					<span><?php echo esc_html( rtrim($labels, ',')) ?></span>
				</div>
			</div>

		<?php
		return true;
	} // end function
		


	public function csl_create_donut($atts) {
		extract( shortcode_atts( array (
			'title' => '',
			'country' => '',
			'heading' => ''
		), $atts ) );	
		
		$data_class = new csl_CoronaData;
		$cs_data = $data_class->country_data($country);
		?>
		<div class="cv-card">
			<div class="cv-card-header">
			<h4 class="flag-heading">
				<?php echo csl_flag_url($country) . " " . esc_html( $country ) ?> 
				<span class="heading-date"> <?php echo date('m-d-Y'); ?></span>
			</h4>
			</div>
			<canvas id="donut-canvas"></canvas>
			<div class="do-donut">
				<?php echo esc_html( $cs_data->cases) ?>,
				<?php echo esc_html( $cs_data->active ); ?>,
				<?php echo esc_html( $cs_data->recovered ); ?>,
				<?php echo esc_html( $cs_data->deaths ); ?>
			</div>

			<div class="container mtop20">
				<div class="row">
					<div class="col-md-4 cv-card-panel red">
						<?php echo number_format(esc_html($cs_data->cases)) ?>
						<span><?php echo esc_html('Total Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel orange">
						<?php echo number_format(esc_html($cs_data->todayCases)) ?>
						<span><?php echo esc_html('New Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel yellow">
						<?php echo number_format(esc_html($cs_data->active)) ?>
						<span><?php echo esc_html('Active Cases', 'csl-corona-stats') ?></span>
					</div>
				</div> <!-- // row -->
				<div class="row mtop10">
					<div class="col-md-4 cv-card-panel">
						<?php echo number_format(esc_html($cs_data->deaths)) ?>
						<span><?php echo esc_html('Total Deaths', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel cyan">
						<?php $new_deaths = ($cs_data->todayDeaths > 0) ? $cs_data->new_deaths : 0; ?>
						<?php echo number_format(esc_html($cs_data->todayDeaths)) ?>
						<span><?php echo esc_html('New Deaths', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel green">
						<?php echo number_format(esc_html($cs_data->recovered)) ?>
						<span><?php echo esc_html('Recovered', 'csl-corona-stats') ?></span>
					</div>
				</div> <!-- // row -->				
			</div>
			</div> <!-- // cv-widget -->
			<?php
			return true;			
		} // end donut fnction
		
		/* ---------------------------------------------------------------------------------------------------------------------------------- */
		
		public function csl_create_sidebar($atts) {
		extract( shortcode_atts( array (
			'title' => '',
			'country' => '',
			'heading' => ''
		), $atts ) );	
		
		$data_class = new csl_CoronaData;
		$cs_data = $data_class->country_data($country);

		?>
		<div class="cv-card">
			<div class="cv-card-header">
			<h4 class="flag-heading">
				<?php echo csl_flag_url($country) . " " . esc_html( $country ) ?> 
				<span class="heading-date"> <?php echo date('m-d-Y'); ?></span>
			</h4>
			</div>
			<div class="container mtop20">
				<div class="row">
					<div class="col-md-4 cv-card-panel red">
						<?php echo number_format(esc_html( $cs_data->cases )) ?>
						<span><?php echo esc_html('Total Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel orange">
						<?php 
							$newCases = ($cs_data->todayCases > 0) ? $cs_data->todayCases : 0; 
							echo number_format(esc_html( $cs_data->todayCases ));
						?>
						<span><?php echo esc_html('New Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel yellow">
						<?php echo number_format(esc_html( $cs_data->active)) ?>
						<span><?php echo esc_html('Active Cases', 'csl-corona-stats') ?></span>
					</div>
				</div> <!-- // row -->
				<div class="row mtop10">
					<div class="col-md-4 cv-card-panel">
						<?php echo number_format(esc_html($cs_data->deaths)) ?>
						<span><?php echo esc_html('Total Deaths', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel cyan">
						<?php 
							$newDeaths = ($cs_data->todayDeaths > 0) ? $cs_data->todayDeaths : 0; 
							echo number_format(esc_html($cs_data->todayDeaths));
						?>
						<span><?php echo esc_html('New Deaths', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-4 cv-card-panel green">
						<?php echo number_format(esc_html($cs_data->recovered)) ?>
						<span><?php echo esc_html('Recovered', 'csl-corona-stats') ?></span>
					</div>
				</div> <!-- // row -->				
			</div>
			</div> <!-- // cv-widget -->
			<?php
			return;			
		} // end donut fnction		
		/* ---------------------------------------------------------------------------------------------------------------------------------- */
		
		public function cs_country_stats($atts) {
		extract( shortcode_atts( array (
			'title' => '',
			'country' => '',
			'heading' => ''
		), $atts ) );	
		
		$data_class = new csl_CoronaData;
		$cs_data = $data_class->country_data($country);

		?>

		<div class="cv-card style-one">
			<div class="container mtop20">
				<h4 class="flag-heading">
					<?php echo csl_flag_url($country) . " " . esc_html( $country ) ?>
					<span class="heading-date"> <?php echo date('m-d-Y'); ?></span>
				</h4>
				<hr>
				<div class="row d-flex">
					<div class="col-md-2 cv-card-panel red">
						<?php echo number_format( esc_html( $cs_data->cases )) ?>
						<span><?php echo esc_html__('Total Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-2 cv-card-panel orange">
						<?php echo number_format( esc_html($cs_data->todayCases)) ?>
						<span><?php echo esc_html__('New Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-2 cv-card-panel yellow">
						<?php echo number_format( esc_html( $cs_data->active )) ?>
						<span><?php echo esc_html__('Active Cases', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-2 cv-card-panel">
						<?php echo number_format( esc_html( $cs_data->deaths )) ?>
						<span><?php echo esc_html__('Total Deaths', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-2 cv-card-panel cyan">
						<?php echo number_format( esc_html($cs_data->todayDeaths)) ?>
						<span><?php echo esc_html__('New Deaths', 'csl-corona-stats') ?></span>
					</div>
					<div class="col-md-2 cv-card-panel green">
						<?php echo number_format( esc_html( $cs_data->recovered )) ?>
						<span><?php echo esc_html__('Recovered', 'csl-corona-stats') ?></span>
					</div>
				</div> <!-- // row -->				
			</div>
		</div> <!-- // cv-card style one -->
			<?php
			return;			
		}

} // end class