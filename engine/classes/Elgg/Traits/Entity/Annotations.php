<?php

namespace Elgg\Traits\Entity;

/**
 * Bundle all annotations related functions for an \ElggEntity
 *
 * @since 6.1
 */
trait Annotations {
	
	/**
	 * Holds annotations until entity is saved.  Once the entity is saved,
	 * annotations are written immediately to the database.
	 *
	 * @var array
	 */
	protected array $temp_annotations = [];
	
	/**
	 * Deletes all annotations on this object (annotations.entity_guid = $this->guid).
	 * If you pass a name, only annotations matching that name will be deleted.
	 *
	 * @warning Calling this with no or empty arguments will clear all annotations on the entity.
	 *
	 * @param string|null $name An optional name of annotations to remove.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function deleteAnnotations(string $name = null): bool {
		if ($this->guid) {
			return elgg_delete_annotations([
				'guid' => $this->guid,
				'limit' => false,
				'annotation_name' => $name,
			]);
		}
		
		if ($name) {
			unset($this->temp_annotations[$name]);
		} else {
			$this->temp_annotations = [];
		}
		
		return true;
	}
	
	/**
	 * Deletes all annotations owned by this object (annotations.owner_guid = $this->guid).
	 * If you pass a name, only annotations matching that name will be deleted.
	 *
	 * @param string|null $name An optional name of annotations to delete.
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function deleteOwnedAnnotations(string $name = null): bool {
		// access is turned off for this because they might
		// no longer have access to an entity they created annotations on
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($name) {
			return elgg_delete_annotations([
				'annotation_owner_guid' => $this->guid,
				'limit' => false,
				'annotation_name' => $name,
			]);
		});
	}
	
	/**
	 * Helper function to return annotation calculation results
	 *
	 * @param string $name        The annotation name.
	 * @param string $calculation A valid MySQL function to run its values through
	 *
	 * @return mixed
	 */
	private function getAnnotationCalculation(string $name, string $calculation): mixed {
		return elgg_get_annotations([
			'guid' => $this->guid,
			'distinct' => false,
			'annotation_name' => $name,
			'annotation_calculation' => $calculation
		]);
	}
	
	/**
	 * Adds an annotation to an entity.
	 *
	 * @warning By default, annotations are private.
	 *
	 * @warning Annotating an unsaved entity more than once with the same name
	 *          will only save the last annotation.
	 *
	 * @todo Update temp_annotations to store an instance of ElggAnnotation and simply call ElggAnnotation::save(),
	 *       after entity is saved
	 *
	 * @param string $name       Annotation name
	 * @param mixed  $value      Annotation value
	 * @param int    $access_id  Access ID
	 * @param int    $owner_guid GUID of the annotation owner
	 * @param string $value_type The type of annotation value
	 *
	 * @return bool|int Returns int if an annotation is saved
	 */
	public function annotate($name, $value, $access_id = ACCESS_PRIVATE, $owner_guid = 0, $value_type = '') {
		if (!$this->guid) {
			$this->temp_annotations[$name] = $value;
			return true;
		}
		
		if (!$owner_guid) {
			$owner_guid = _elgg_services()->session_manager->getLoggedInUserGuid();
		}
		
		$annotation = new \ElggAnnotation();
		$annotation->entity_guid = $this->guid;
		$annotation->name = $name;
		$annotation->value = $value;
		$annotation->owner_guid = $owner_guid;
		$annotation->access_id = $access_id;
		
		if (!empty($value_type)) {
			$annotation->value_type = $value_type;
		}
		
		if ($annotation->save()) {
			return $annotation->id;
		}
		
		return false;
	}
	
	/**
	 * Gets an array of annotations.
	 *
	 * To retrieve annotations on an unsaved entity, pass array('name' => [annotation name])
	 * as the options array.
	 *
	 * @param array $options Array of options for elgg_get_annotations() except guid.
	 *
	 * @return \ElggAnnotation[]|mixed
	 * @see elgg_get_annotations()
	 */
	public function getAnnotations(array $options = []) {
		if ($this->guid) {
			$options['guid'] = $this->guid;
			
			return elgg_get_annotations($options);
		} else {
			$name = elgg_extract('annotation_name', $options, '');
			
			if (isset($this->temp_annotations[$name])) {
				return [$this->temp_annotations[$name]];
			}
		}
		
		return [];
	}
	
	/**
	 * Count annotations.
	 *
	 * @param string $name The type of annotation.
	 *
	 * @return int
	 */
	public function countAnnotations(string $name = ''): int {
		return $this->getAnnotationCalculation($name, 'count');
	}
	
	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsAvg(string $name) {
		return $this->getAnnotationCalculation($name, 'avg');
	}
	
	/**
	 * Get the sum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsSum(string $name) {
		return $this->getAnnotationCalculation($name, 'sum');
	}
	
	/**
	 * Get the minimum of integer type annotations of given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsMin(string $name) {
		return $this->getAnnotationCalculation($name, 'min');
	}
	
	/**
	 * Get the maximum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsMax(string $name) {
		return $this->getAnnotationCalculation($name, 'max');
	}
}
