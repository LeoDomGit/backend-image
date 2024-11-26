import React, { useEffect, useState } from 'react';
import Layout from '../../Components/Layout';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import { Box } from "@mui/material";
import { DataGrid } from '@mui/x-data-grid';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import axios from 'axios';
import Swal from 'sweetalert2';

function Index({ features }) {
  const [feature, setFeature] = useState({ title: '', slug: '', api: '' });
  const [data, setData] = useState(features);
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const formatCreatedAt = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString();
};
  const apiBase = '/';

  const submitFeature = () => {
    axios.post(`${apiBase}features`, feature)
      .then((res) => {
        if (res.data.check) {
          toast.success("Feature added successfully");
          setData(res.data.data);
          setShow(false);
          setFeature({ title: '', slug: '', api: '' });
        } else {
          toast.error(res.data.msg);
        }
      });
  };

  const handleCellEditStop = (id, field, value) => {
    if (value === '') {
      Swal.fire({
        icon: 'question',
        text: "Do you want to delete this feature?",
        showDenyButton: true,
        confirmButtonText: "Yes",
        denyButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`${apiBase}features/${id}`).then((res) => {
            if (res.data.check) {
              toast.success("Feature deleted successfully");
              setData(res.data.data);
            }
          });
        }
      });
    } else {
      axios.put(`${apiBase}features/${id}`, { [field]: value })
        .then((res) => {
          if (res.data.check) {
            toast.success("Feature updated successfully");
            setData(res.data.data);
          } else {
            toast.error(res.data.msg);
          }
        });
    }
  };

  const columns = [
    { field: "id", headerName: "#", width: 50 },
    { field: 'title', headerName: "Title", width: 150, editable: true },
    { field: 'slug', headerName: "Slug", width: 150, editable: true },
    { field: 'api', headerName: "API Endpoint", width: 200, editable: true },
    { field: 'created_at', headerName: 'Created at', width: 200, valueGetter: (params) => formatCreatedAt(params)}
  ];

  return (
    <Layout>
      <ToastContainer />
      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Create Feature</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <input type="text" className='form-control mb-2' placeholder="Title"
            value={feature.title} onChange={(e) => setFeature({ ...feature, title: e.target.value })} />
          <input type="text" className='form-control mb-2' placeholder="Slug"
            value={feature.slug} onChange={(e) => setFeature({ ...feature, slug: e.target.value })} />
          <input type="text" className='form-control' placeholder="API Endpoint"
            value={feature.api} onChange={(e) => setFeature({ ...feature, api: e.target.value })} />
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>Close</Button>
          <Button variant="primary" disabled={!feature.title || !feature.slug || !feature.api}
            onClick={submitFeature}>Create</Button>
        </Modal.Footer>
      </Modal>

      <Button className="btn btn-primary mb-3" onClick={handleShow}>Create New Feature</Button>

      <Box sx={{ height: 400, width: '100%' }}>
        <DataGrid
          rows={data}
          columns={columns}
          initialState={{
            pagination: { paginationModel: { pageSize: 5 } },
          }}
          pageSizeOptions={[5]}
          onCellEditStop={(params, e) =>
            handleCellEditStop(params.id, params.field, e.target.value)
          }
        />
      </Box>
    </Layout>
  );
}

export default Index;
